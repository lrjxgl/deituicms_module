<?php
	class pdd_cartControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$userid=M("login")->userid;
			$shopid=get("shopid","i");
			$where="userid=".$userid;
			if($shopid){
				$where.="  shopid=".$shopid;
			}
			$cartList=MM("pdd","pdd_cart")->Dselect(array(
				"where"=>$where
			));
			$shopids=array();
			$spcarts=array();
			$shopCarts=array();
			if($cartList){
				foreach($cartList as $v){
					$shopids[]=$v["shopid"];
					$spcarts[$v["shopid"]][]=$v;
				}
				$shopids=array_unique($shopids);
				$shops=MM("pdd","pdd_shop")->getListByIds($shopids);
				foreach($shops as $k=>$shop){
					$cart=$spcarts[$v["shopid"]];
					$total_num=0;
					$total_money=0;
					foreach($cart as $v){
						$total_num+=$v["amount"];
						$total_money+=$v["amount"]*$v["price"];
					}
					$shop["cart"]=$spcarts[$v["shopid"]];
					$shop["total_num"]=$total_num;
					$shop["total_money"]=$total_money;
					$shopCarts[$k]=$shop;
				}
			}
			 
			
			$this->smarty->goAssign(array(
				"shopCarts"=>$shopCarts,
			));
			$this->smarty->display("pdd_cart/index.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$shopid=get("shopid","i");
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			$product=MM("pdd","pdd_product")->selectRow("id=".$productid);
			$amount=get("amount","i");
			$ks=MM("pdd","pdd_product_ks")->selectRow("id=".$ksid);
			if($ks){
				if($ks["total_num"]<$amount){
					$this->goAll("库存不足",1);
				}
			}else{
				if($product["total_num"]<$amount){
					$this->goAll("库存不足",1);
				}
			}
			
			
			$cart=MM("pdd","pdd_cart")->selectRow("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			if($amount<=0){
				MM("pdd","pdd_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
				$rdata=array(
					"action"=>"delete",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>0
				);
			}elseif($cart){
				MM("pdd","pdd_cart")->update(array(	
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
				$cart["id"]=MM("pdd","pdd_cart")->insert(array(	
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
			MM("pdd","pdd_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			$this->goAll("删除成功");
		}
		public function onClear(){
			$userid=M("login")->userid;
			MM("pdd","pdd_cart")->delete("userid=".$userid);
			$this->goAll("清除成功");
		}
		
	}