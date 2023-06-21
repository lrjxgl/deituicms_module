<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class freeshop_shop_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h"); 
			$url="/moduleadmin.php?m=freeshop_shop_apply&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			switch($type){
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
					break;
				default:
						$where=" status=0 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_freeshop_shop_apply")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["yyzz"]=images_site($v["yyzz"]);
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
			$this->smarty->display("freeshop_shop_apply/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_freeshop_shop_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_freeshop_shop_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$id=get("id","i");
			$row=M("mod_freeshop_shop_apply")->selectRow("id=".$id);
			if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			$_POST=$row;
			$indata=M("mod_freeshop_shop")->postData();
			$indata["shopname"]=$row["title"];
			$indata["status"]=1;
			unset($indata["id"]);
			 
			$shopid=M("mod_freeshop_shop")->insert($indata);
			 
			M("mod_freeshop_shop_apply")->update(array(
				"status"=>1
			),"id=".$id);
			$config=M("mod_freeshop_config")->selectRow("1");
			MM("freeshop","freeshop_shop_money")->addMoney(array(
				"balance"=>$config["shop_join_money"],
				"shopid"=>$shopid,
				"content"=>"商家入驻平台奖励".$money."元"
			));
			//设置抽成
			M("mod_freeshop_shop_commission")->insert(array(
				"per"=>$row["per_money"],
				"stime"=>time(),
				"etime"=>time()+3600*24*365,
				"shopid"=>$shopid
			));
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$id=get("id","i");
			$row=M("mod_freeshop_shop_apply")->selectRow("id=".$id);
				if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			M("mod_freeshop_shop_apply")->update(array(
				"status"=>2
			),"id=".$id);
			$this->goAll("禁止成功");
		}
	}

?>