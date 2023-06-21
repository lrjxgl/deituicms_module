<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gxny_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=gxny_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_order")->select($option,$rscount);
			if(!empty($data)){
				$proids=[];
				$uids=[];
				foreach($data as $v){
					$proids[]=$v["productid"];
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				$pros=MM("gxny","gxny_shop_product")->getListByIds($proids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["product"]=$pros[$v["productid"]];
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
			$this->smarty->display("gxny_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_gxny_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gxny_order/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			M("mod_gxny_order")->update(array("status"=>$status),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		
	}

?>