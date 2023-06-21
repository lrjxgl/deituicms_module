<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bill_shop_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid)); 
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$where=" shopid=".$shopid." AND status in(0,1,2)";
			$url="/module.php?m=bill_shop_category&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" catid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_shop_category")->select($option,$rscount);
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
			$this->smarty->display("bill_shop_category/index.html");
		}
		
		public function onAdmin(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$where=" shopid=".$shopid." AND status in(0,1,2)";
			$url="/module.php?m=bill_shop_category&a=admin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" catid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_shop_category")->select($option,$rscount);
			if($data){
				$catids=array();		
				foreach($data as $v){
					$catids[]=$v["catid"];
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
					"url"=>$url,
					"shop"=>$shop
				)
			);
			$this->smarty->display("bill_shop_category/admin.html");
		}
		
		public function onShow(){
			$catid=get_post("catid","i");
			$data=M("mod_bill_shop_category")->selectRow(array("where"=>"catid=".$catid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("bill_shop_category/show.html");
		}
		public function onAdd(){
			$catid=get_post("catid","i");
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($catid){
				$data=M("mod_bill_shop_category")->selectRow(array("where"=>"catid=".$catid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"shop"=>$shop
			));
			$this->smarty->display("bill_shop_category/add.html");
		}
		
		public function onSave(){
			$catid=get_post("catid","i");
			
			$data=M("mod_bill_shop_category")->postData();
			$data["status"]=1;
			if($catid){
				
				$cat=M("mod_bill_shop_category")->selectRow("catid=".$catid);
				$shopid=$cat["shopid"];
				$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
				$userid=M("login")->userid;
				if($shop["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_bill_shop_category")->update($data,"catid='$catid'");
			}else{
				$shopid=$data["shopid"];
				$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
				$userid=M("login")->userid;
				if($shop["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				} 
				$data["dateline"]=time();
				M("mod_bill_shop_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$catid=get_post('catid',"i");
			$cat=M("mod_bill_shop_category")->selectRow("catid=".$catid);
			$shopid=$cat["shopid"];
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$status=get_post("status","i");
			M("mod_bill_shop_category")->update(array("status"=>$status),"catid=$catid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			$cat=M("mod_bill_shop_category")->selectRow("catid=".$catid);
			$shopid=$cat["shopid"];
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_bill_shop_category")->update(array("status"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>