<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bill_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$userid=M("login")->userid;
		
			$where=" userid=".$userid." AND status in(0,1,2)";
			
			$limit=20;
			$start=get("per_page","i");
			$url="/module.php?m=bill_shop";
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_shop")->select($option,$rscount);
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
			$this->smarty->display("bill_shop/index.html");
		}
		
		public function onShow(){
			$shopid=get_post("shopid","i");
			$data=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$income=M("mod_bill_log")->selectOne(array(
				"where"=>" shopid=".$shopid." AND status=1  AND money>0",
				"fields"=>"sum(money) as money"
			));
			$outcome=M("mod_bill_log")->selectOne(array(
				"where"=> "  shopid=".$shopid." AND status=1  AND money<0 ",
				"fields"=>"sum(money) as money"
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"income"=>$income,
				"outcome"=>$outcome
			));
			$this->smarty->display("bill_shop/show.html");
		}
		public function onAdd(){
			$shopid=get_post("shopid","i");
			$userid=M("login")->userid;
			if($shopid){
				$data=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
				if($data["userid"]!=$userid){
					$this->goAll("暂无权利",1);
				}
			}
			
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("bill_shop/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$shopid=get_post("shopid","i");
			$data=M("mod_bill_shop")->postData();
			if($shopid){
				$row=M("mod_bill_shop")->selectRow("shopid=".$shopid);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_bill_shop")->update($data,"shopid='$shopid'");
			}else{
				$data["userid"]=$userid;
				$data["dateline"]=time();
				M("mod_bill_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onAdmin(){
			$userid=M("login")->userid;
			$shopid=get_post('shopid',"i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid));
			 
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$income=M("mod_bill_log")->selectOne(array(
				"where"=>" shopid=".$shopid." AND status=1  AND money>0",
				"fields"=>"sum(money) as money"
			));
			$outcome=M("mod_bill_log")->selectOne(array(
				"where"=> "  shopid=".$shopid." AND status=1  AND money<0 ",
				"fields"=>"sum(money) as money"
			));
			$catList=M("mod_bill_shop_category")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"order"=>"catid DESC"
			));
			$this->smarty->goassign(array(
				"shop"=>$shop,
				"income"=>$income,
				"outcome"=>$outcome,
				"catList"=>$catList
			));
			$this->smarty->display("bill_shop/admin.html");
		}
		
		 
		
		
	}

?>