<?php
	class f2c_cartControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$userid=M("login")->userid;
			$cartList=MM("f2c","f2c_cart")->Dselect(array(
				"where"=>" userid=".$userid
			));
			$total_num=0;
			$total_money=0;
			if(!empty($cartList)){
				foreach($cartList as $v){
					$total_num+=$v["amount"];
					$total_money+=$v["amount"]*$v["price"];
				}
			}
			$this->smarty->goAssign(array(
				"cartList"=>$cartList,
				"total_num"=>$total_num,
				"total_money"=>$total_money
			));
			$this->smarty->display("f2c_cart/index.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			$product=MM("f2c","f2c_product")->selectRow("id=".$productid);
			$amount=get("amount","i");
			$ks=MM("f2c","f2c_product_ks")->selectRow("id=".$ksid);
			if($ks){
				if($ks["total_num"]<$amount){
					$this->goAll("库存不足",1);
				}
			}else{
				if($product["total_num"]<$amount){
					$this->goAll("库存不足",1);
				}
			}
			
			
			$cart=MM("f2c","f2c_cart")->selectRow("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			if($amount<=0){
				MM("f2c","f2c_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
				$rdata=array(
					"action"=>"delete",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>0
				);
			}elseif($cart){
				MM("f2c","f2c_cart")->update(array(	
					"createtime"=>date("Y-m-d H:i:s"),
					"amount"=>$amount
				),"id=".$cart["id"]);
				$rdata=array(
					"action"=>"update",
					"cartid"=>$cart["id"],
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>$amount
				);
			}else{
				$cart["id"]=MM("f2c","f2c_cart")->insert(array(	
					"createtime"=>date("Y-m-d H:i:s"),
					"amount"=>$amount,
					"ksid"=>$ksid,
					"productid"=>$productid,
					"userid"=>$userid
				));
				$rdata=array(
					"cartid"=>$cart["id"],
					"action"=>"add",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>$amount
				);
			}
			
			$this->goAll("加入成功",0,$rdata);
		}
		 
		public function onDelete(){
			$userid=M("login")->userid;
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			MM("f2c","f2c_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			$this->goAll("删除成功");
		}
		public function onClear(){
			$userid=M("login")->userid;
			MM("f2c","f2c_cart")->delete("userid=".$userid);
			$this->goAll("清除成功");
		}
		
	}