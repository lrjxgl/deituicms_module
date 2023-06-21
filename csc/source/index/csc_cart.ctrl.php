<?php
	class csc_cartControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			if(!in_array(get("a"),array("stat"))){
				M("login")->checkLogin();
			}
			
		}
		public function onDefault(){
			$userid=M("login")->userid;
			$shopid=SHOPID;
			$shop=MM("csc","csc_shop")->get($shopid);
			$where="userid=".$userid;
			if($shopid){
				$where.=" AND shopid=".$shopid;
			}
			$cartList=MM("csc","csc_cart")->Dselect(array(
				"where"=>$where
			));
			 
			$total_money=$total_num=0;
			if($cartList){
				foreach($cartList as $k=>$v){
					$total_num+=$v["amount"];
					$total_money+=$v["amount"]*$v["price"];
				}
			}
			 
			
			$this->smarty->goAssign(array(
				"cartList"=>$cartList,
				"total_num"=>$total_num,
				"total_money"=>$total_money,
				"shop"=>$shop
			));
			$this->smarty->display("csc_cart/index.html");
		}
		
		public function onStat(){
			$userid=M("login")->userid;
			if(!$userid){
				$total_num=0;
				$total_money=0;
			}else{
				$shopid=SHOPID;
				$where.="userid=".$userid." AND shopid=".$shopid;
				$cartList=MM("csc","csc_cart")->Dselect(array(
					"where"=>$where,
				));
				 
				$total_money=$total_num=0;
				if($cartList){
					foreach($cartList as $k=>$v){
						$total_num+=$v["amount"];
						$total_money+=$v["amount"]*$v["price"];
					}
				}
			}
			$this->goall("success",0,array(
				 
				"total_num"=>$total_num,
				"total_money"=>$total_money,
				 
			));
		}
		
		public function onAdd(){
			$userid=M("login")->userid;
			$shopid=SHOPID;
			$shop=M("mod_csc_shop")->selectRow(array(
				"where"=>"shopid=".SHOPID,
				"fields"=>" shopid,yystatus"
			));
			if($shop["yystatus"]!=1){
				$this->goAll("店铺装修中，即将营业",1);
			}
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			$product=MM("csc","csc_product")->selectRow("id=".$productid);
			$amount=get("amount","i");
			$cart=MM("csc","csc_cart")->selectRow("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			$ks=MM("csc","csc_product_ks")->selectRow("id=".$ksid);
			if($ks){
				if($ks["total_num"]<$amount){
					if($cart["amount"]<$amount){
						$this->goAll("库存不足",1);
					}
					
				}
			}else{
				if($product["total_num"]<$amount){
					if($cart["amount"]<$amount){
						$this->goAll("库存不足",1);
					}
				}
			}
			
			
			$cart=MM("csc","csc_cart")->selectRow("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			if($amount<=0){
				MM("csc","csc_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
				$rdata=array(
					"action"=>"delete",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>0
				);
			}elseif($cart){
				MM("csc","csc_cart")->update(array(	
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
				$cart["id"]=MM("csc","csc_cart")->insert(array(	
					"createtime"=>date("Y-m-d H:i:s"),
					"amount"=>$amount,
					"ksid"=>$ksid,
					"productid"=>$productid,
					"userid"=>$userid,
					"shopid"=>$shopid
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
			MM("csc","csc_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			$this->goAll("删除成功");
		}
		public function onClear(){
			$userid=M("login")->userid;
			MM("csc","csc_cart")->delete("userid=".$userid);
			$this->goAll("清除成功");
		}
		
	}