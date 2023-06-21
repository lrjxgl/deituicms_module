<?php
	class s2c_cartControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$userid=M("login")->userid;
			$cartList=MM("s2c","s2c_cart")->Dselect(array(
				"where"=>" userid=".$userid." AND teamid=".TEAMID
			));
		 
			$total_num=0;
			$total_money=0;
			if(!empty($cartList)){
				foreach($cartList as $v){
					$total_num+=$v["amount"];
					$total_money+=$v["amount"]*$v["price"];
				}
			}
			$sendTime=MM("s2c","s2c_order")->getSendTime();
			$this->smarty->goAssign(array(
				"cartList"=>$cartList,
				"total_num"=>$total_num,
				"total_money"=>$total_money,
				"sendTime"=>$sendTime
			));
			$this->smarty->display("s2c_cart/index.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			$product=MM("s2c","s2c_product")->selectRow("id=".$productid);
			$amount=get("amount","i");
			$ks=MM("s2c","s2c_product_ks")->selectRow("id=".$ksid);
			if($ks){
				if($ks["total_num"]<$amount){
					$this->goAll("库存不足",1);
				}
			}else{
				if($product["total_num"]<$amount){
					$this->goAll("库存不足",1);
				}
			}
			
			
			$cart=MM("s2c","s2c_cart")->selectRow("userid=".$userid." AND teamid=".TEAMID." AND productid=".$productid." AND ksid=".$ksid);
			if($amount<=0){
				MM("s2c","s2c_cart")->delete("userid=".$userid." AND teamid=".TEAMID." AND productid=".$productid." AND ksid=".$ksid);
				$rdata=array(
					"action"=>"delete",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>0,
					"teamid"=>TEAMID
				);
			}elseif($cart){
				MM("s2c","s2c_cart")->update(array(	
					"createtime"=>date("Y-m-d H:i:s"),
					"amount"=>$amount
				),"id=".$cart["id"]);
				$rdata=array(
					"action"=>"update",
					"cartid"=>$cart["id"],
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>$amount,
					"teamid"=>TEAMID
				);
			}else{
				$cart["id"]=MM("s2c","s2c_cart")->insert(array(	
					"createtime"=>date("Y-m-d H:i:s"),
					"amount"=>$amount,
					"ksid"=>$ksid,
					"productid"=>$productid,
					"userid"=>$userid,
					"teamid"=>TEAMID
				));
				$rdata=array(
					"cartid"=>$cart["id"],
					"action"=>"add",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>$amount,
					"teamid"=>TEAMID
				);
			}
			
			$this->goAll("加入成功",0,$rdata);
		}
		 
		public function onDelete(){
			$userid=M("login")->userid;
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			MM("s2c","s2c_cart")->delete("userid=".$userid." AND teamid=".TEAMID." AND productid=".$productid." AND ksid=".$ksid);
			$this->goAll("删除成功");
		}
		public function onClear(){
			$userid=M("login")->userid;
			MM("s2c","s2c_cart")->delete("userid=".$userid);
			$this->goAll("清除成功");
		}
		
	}