<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bill_shop_userControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=bill_shop_user&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" suid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_shop_user")->select($option,$rscount);
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
			$this->smarty->display("bill_shop_user/index.html");
		}
		
		public function onShow(){
			$suid=get_post("suid","i");
			$data=M("mod_bill_shop_user")->selectRow(array("where"=>"suid=".$suid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("bill_shop_user/show.html");
		}
		
		public function onAdmin(){
			$shopid=get("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$where=" shopid=".$shopid." AND status in(0,1,2)";
			$url="/module.php?m=bill_shop_user&a=admin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" suid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_shop_user")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"shop"=>$shop
				)
			);
			$this->smarty->display("bill_shop_user/admin.html");
		}
		
		public function onAdd(){
			$suid=get_post("suid","i");
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($suid){
				$data=M("mod_bill_shop_user")->selectRow(array("where"=>"suid=".$suid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"shop"=>$shop
			));
			$this->smarty->display("bill_shop_user/add.html");
		}
		
		public function onSave(){
			$suid=get_post("suid","i");
			$data=M("mod_bill_shop_user")->postData();
			if($suid){
				$data["status"]=1;
				$cat=M("mod_bill_shop_user")->selectRow("suid=".$suid);
				$shopid=$cat["shopid"];
				$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
				$userid=M("login")->userid;
				if($shop["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_bill_shop_user")->update($data,"suid='$suid'");
			}else{
				$data["status"]=1;
				$shopid=$data["shopid"];
				$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
				$userid=M("login")->userid;
				if($shop["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				} 
				M("mod_bill_shop_user")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$suid=get_post('suid',"i");
			$cat=M("mod_bill_shop_user")->selectRow("suid=".$suid);
			$shopid=$cat["shopid"];
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$status=get_post("status","i");
			M("mod_bill_shop_user")->update(array("status"=>$status),"suid=$suid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$suid=get_post('suid',"i");
			$cat=M("mod_bill_shop_user")->selectRow("suid=".$suid);
			$shopid=$cat["shopid"];
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_bill_shop_user")->update(array("status"=>11),"suid=$suid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>