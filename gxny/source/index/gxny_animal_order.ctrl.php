<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gxny_animal_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$type=get("type","h");
			$where=" status in(0,1,2)";
			$url="module.php?m=gxny_animal_order&a=default";
			switch($type){
				case "doing":
					$where=" status=1 ";
					break;
				case "cancel":
					$where=" status=4 ";
					break;
				case "finish":
					$where=" status=3 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_animal_order")->select($option,$rscount);
			if(!empty($data)){
				$shopids=[];
				$ids=[];
				foreach($data as $k=>$v){
					$shopids[]=$v["shopid"];
					$ids[]=$v["animalid"];
				}
				$sps=MM("gxny","gxny_shop")->getListByIds($shopids);
				$ans=MM("gxny","gxny_shop_animal")->getListByIds($ids);
				$statusList=array(
					0=>"待确认",
					1=>"进行中",
					3=>"已完成",
					4=>"已取消"
				);
				foreach($data as $k=>$v){
					$v["shop"]=$sps[$v["shopid"]];
					$v["animal"]=$ans[$v["animalid"]];
					$v["status_name"]=$statusList[$v["status"]];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gxny_animal_order/index.html");
		}
		
		public function onShow(){
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");
			$data=M("mod_gxny_animal_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gxny_animal_order/show.html");
		}
		
		public function onOut(){
			$userid=M("login")->userid;
			$orderid=get_post("orderid","i");
			$order=M("mod_gxny_animal_order")->selectRow(array("where"=>"orderid=".$orderid));
			if($order["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($order["status"]>=3){
				$this->goAll("订单已结束，无法处理",1);
			}
			M("mod_gxny_animal_order")->begin();
			M("mod_gxny_animal_order")->update(array(
				"status"=>4
			),"orderid=".$orderid);
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>$order["back_money"],
				"content"=>"取消领养，返回".$order["back_money"]."元"
			));
			M("mod_gxny_animal_order")->commit();
			$this->goAll("取消成功");
		}
		
	}

?>