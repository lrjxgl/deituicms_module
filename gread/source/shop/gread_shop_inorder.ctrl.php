<?php
	class gread_shop_inorderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			
			$option=array();
			$rscount=true;
			$data=M("mod_gread_shop_inorder")->select($option,$rscount);
			$status_list=MM("gread","gread")->order_status_list();
			if($data){
				foreach($data as $v){
					$oids[]=$v['orderid'];
				}
				$order_products=M("mod_gread_shop_inorder_book")->select(array(
					"where"=>" orderid in("._implode($oids).") ",
					"fields"=>"bookid,orderid,num,price"
				));
				foreach($order_products as $v){
					$bookids[]=$v['bookid'];
				}
				$books=MM("gread","gread_book")->getListByIds($bookids);
				foreach($order_products as $v){
					$v['title']=$books[$v['bookid']]['title'];
					$v['imgurl']=$books[$v['bookid']]['imgurl'];
					$pros[$v['orderid']][]=$v;
				}
				foreach($data as &$v){
					$v['pros']=$pros[$v['orderid']];
					$v['status_name']=$status_list[$v['status']];
				}
			}
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_shop_inorder/index.html");
		}
		
		public function onBuy(){
			$sql=" select a.bookid,a.num,b.price 
				from ".table('mod_gread_shop_incart')." as a 
				left join ".table('mod_gread_book')." as b
				on a.bookid=b.bookid
				where a.shopid=".SHOPID ;
			$carts=M("mod_gread_shop_incart")->getAll($sql);
			if(empty($carts)){
				$this->goAll("请先选择商品",1);
			}
			$bookmoney=$money=0;
			foreach($carts as $v){
				$bookmoney+=$v['num']*$v['price'];
			}
			$money=$bookmoney;
			$shop=M("mod_gread_shop")->selectRow("shopid=".SHOPID);
			$createtime=date("Y-m-d H:i:s");
			$orderid=M("mod_gread_shop_inorder")->insert(array(
				"shopid"=>SHOPID,
				"createtime"=>$createtime,
				"money"=>$money,
				"bookmoney"=>$bookmoney,
				"telephone"=>$shop['telephone'],
				"nickname"=>$shop['nickname'],
				"address"=>$shop['address']
				
			));
			foreach($carts as $v){
				$inserts[]=array(
					"shopid"=>SHOPID,
					"bookid"=>$v['bookid'],
					"orderid"=>$orderid,
					"num"=>$v['num'],
					"createtime"=>$createtime,
					"price"=>$v['price']
				);
				//更新店铺书籍数量
				$row=M("mod_gread_shop_product")->selectRow("bookid=".$v['bookid']." AND shopid=".SHOPID);
				M("mod_gread_shop_product")->update(array(
					"status"=>2,
					"total_num"=>$row['total_num']+$v['num']
				),"bookid=".$v['bookid']." AND shopid=".SHOPID);
			}
			M("mod_gread_shop_inorder_book")->insertmore($inserts);
			M("mod_gread_shop_incart")->delete("shopid=".SHOPID);
			$this->goALl("下单成功");
			
		}
		
		public function onFinish(){
			$orderid=get('orderid','i');
			$order=M("mod_gread_shop_inorder")->selectRow("orderid=".$orderid." AND shopid=".SHOPID);
			if(empty($order)){
				$this->goAll("订单出错",1);
			}
			if($order["status"]>=3){
				$this->goAll("订单已处理了",1);
			}
			M("mod_gread_shop_inorder")->update(array(
				"status"=>3
			),"orderid=".$orderid." AND shopid=".SHOPID);
			//图书上架
			$pros=M("mod_gread_shop_inorder_book")->select(array(
				"where"=>" orderid=".$orderid,
				"fields"=>"bookid,num"
			));
			foreach($pros as $v){
				M("mod_gread_shop_product")->changenum("total_num",$v['num'],"shopid=".SHOPID." AND bookid=".$v['bookid']);
			}
			$this->goAll("订单处理完成");
		}
		
		public function onCancel(){
			$orderid=get('orderid','i');
			M("mod_gread_shop_inorder")->update(array(
				"status"=>11
			),"orderid=".$orderid." AND shopid=".SHOPID);
			$this->goAll("取消订单成功");
		}
		
	}
?>