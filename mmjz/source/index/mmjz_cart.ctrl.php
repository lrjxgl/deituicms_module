<?php
	class mmjz_cartControl extends skymvc{
		
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
				$where.=" AND  shopid=".$shopid;
			}
			$cartList=MM("mmjz","mmjz_cart")->Dselect(array(
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
				$shops=MM("mmjz","mmjz_shop")->getListByIds($shopids);
				foreach($shops as $k=>$shop){
					$cart=$spcarts[$v["shopid"]];
					$total_num=0;
					$total_money=0;
					foreach($cart as $v){
						$total_num+=$v["amount"];
						$total_money+=$v["amount"]*$v["price"];
					}
					$shop["cart"]=$spcarts[$shop["shopid"]];
					$shop["total_num"]=$total_num;
					$shop["total_money"]=$total_money;
					$shopCarts[$k]=$shop;
				}
			}
			 
			
			$this->smarty->goAssign(array(
				"shopCarts"=>$shopCarts,
			));
			$this->smarty->display("mmjz_cart/index.html");
		}
		public function onAdd(){
			$userid=M("login")->userid;
			$shopid=get("shopid","i");
			$productid=get("productid","i");
			$ksid=get("ksid","i");
			$product=MM("mmjz","mmjz_product")->selectRow("id=".$productid);
			$shopid=$product["shopid"];
			$shop=M("mod_mmjz_shop")->selectRow(array(
				"where"=>"shopid=".$shopid,
				"fields"=>" shopid,yystatus"
			));
			if($shop["yystatus"]!=1){
				$this->goAll("店铺装修中，即将营业",1);
			}
			$amount=get("amount","i");
			$cart=MM("mmjz","mmjz_cart")->selectRow("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			$ks=MM("mmjz","mmjz_product_ks")->selectRow("id=".$ksid);
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
			
			
			$cart=MM("mmjz","mmjz_cart")->selectRow("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			if($amount<=0){
				MM("mmjz","mmjz_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
				$rdata=array(
					"action"=>"delete",
					"ksid"=>$ksid,
					"productid"=>$productid,
					"amount"=>0
				);
			}elseif($cart){
				MM("mmjz","mmjz_cart")->update(array(	
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
				$cart["id"]=MM("mmjz","mmjz_cart")->insert(array(	
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
			MM("mmjz","mmjz_cart")->delete("userid=".$userid." AND productid=".$productid." AND ksid=".$ksid);
			$this->goAll("删除成功");
		}
		public function onClear(){
			$userid=M("login")->userid;
			MM("mmjz","mmjz_cart")->delete("userid=".$userid);
			$this->goAll("清除成功");
		}
		
		public function onStatShop(){
			$userid=M("login")->userid;
			$shopid=get("shopid","i");
			$cartList=MM("mmjz","mmjz_cart")->Dselect(array(
				"where"=>" shopid=".$shopid." AND userid=".$userid
			));
			 
			$cart_total_num=0;
			$cart_total_money=0;
			$weight=0;
			if($cartList){
				foreach($cartList as $v){
					$weight+=$v["amount"]*$v["weight"];
					$cart_total_num+=$v["amount"];
					$cart_total_money+=$v["amount"]*$v["price"];
				}
			}
			$express_money=MM("mmjz","mmjz_express_fee")->getMoney($user_address_id,$weight,$shopid,$shop["express_money"]);
			echo json_encode(array(
				"error"=>0,
				"data"=>array(
					"cart_total_num"=>$cart_total_num,
					"cart_total_money"=>$cart_total_money,
					"express_money"=>$express_money,
					 
				)
			));
			
		}
		
		public function onGetByShop(){
			M("login")->checkLogin();
			$shopid=get("shopid","i");
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND shopid=".$shopid;
			$cartList=MM("mmjz","mmjz_cart")->Dselect(array(
				"where"=>$where
			));
			$total_num=0;
			$total_money=0;
			if(!empty($cartList)){
				foreach($cartList as $v){
					$total_num+=$v["amount"];
					$total_money+=$v["amount"]*$v["price"];
				}
			}
			
			echo json_encode(array(
				"error"=>0,
				"message"=>"success",
				"data"=>array(
					"list"=>$cartList,
					"total_num"=>$total_num,
					"total_money"=>$total_money
				)
			));
		}
		
	}