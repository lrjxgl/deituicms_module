<?php
class mmjz_shop_noticeModel extends model{
	public $queue=false;
	public $table="mod_mmjz_shop_notice";
	
	public function sendNewOrder($orderid){
		require_once "extends/pushtpl.class.php";
		
		$order=M("mod_mmjz_order")->selectRow("orderid=".$orderid);
		if(empty($order)){
			return false;
		}
		//发送打印
		$shopid=$order["shopid"];
		$order["status_name"]=MM("mmjz","mmjz_order")->getStatus($order);
		$orderdata=MM("mmjz","mmjz_order_data")->get($orderid);
		$products="";
		$printer_products=[];
		foreach($orderdata["prolist"] as $p){
			$products.=$p["title"]."x".$p["price"]."\r\n";
			$printer_products[]=array(
				"title"=>$p['title'],
				"price"=>$p['price'],
				"amount"=>$p['amount'],
				"money"=>$p['price']*$p['amount'],
			);
		}
		//发送打印消息
		if(M("module")->isInstall("printer")){
			$printer=M("mod_printer")->selectRow("tablename='mmjz_shop' AND bstatus=2 AND shopid=".$order["shopid"]);
			if($printer){
				$printer_order=array(
					"money"=>$order["money"],
					"dateline"=>time(),
					"pay_type"=>$order["pay_type"],
					"orderno"=>$order["orderno"],
					"comment"=>$order["comment"]
				);
				$printer_address=array(
					"address"=>$orderdata["address"]["address"],
					"telephone"=>$orderdata["address"]['telephone'],
					"nickname"=>$orderdata["address"]['truename']
				);
				$printer_content=array(
					"products"=>$printer_products,
					"addr"=>$printer_address,
					"order"=>$printer_order,
				);
				$printer_content=arr2str($printer_content);
				M("mod_printer_plan")->insert(array(
					"tablename"=>"mmjz_shop",
					"shopid"=>$order["shopid"],
					"title"=>"订单".$order["orderno"],
					"dateline"=>time(),
					"last_time"=>time(),
					"ftable"=>"mmjz_shop_order",
					"fid"=>$order_id,
					"content"=>$printer_content,
					"isvalid"=>1
				));
			}
		}	
		//发送wx模板消息
		$open=M("mod_mmjz_shop_openbind")->selectRow("shopid=".SHOPID." AND k='weixin' ");
		if(empty($open)){
			return false;
		}
		$od=str2arr($open["content"]);
		$tpl=pushTpl::wxList("neworder");
	 
		if($token=get_weixin_access_token()){
			$ops=array(
				"touser"=>$od["openid"],
				"template_id"=>$tpl["key"],
				"url"=>HTTP_HOST."/moduleshop.php?m=mmjz_order&a=show&orderid=".$orderid,
				"data"=>array(
					"first"=>array("value"=>"店铺新订单成交通知"),
					"keyword1"=>array("value"=>$order["shop_money"]),
					"keyword2"=>array("value"=>$order["status_name"]),
					"keyword3"=>array("value"=>$order["orderno"]),
					"keyword4"=>array("value"=>$orderdata["address"]["truename"]),
					"remark"=>array("value"=>$products),
				)
			);
			$res=wx_mb_send($token["access_token"],$ops);
			 
			return true;
		}
		return false;
	}
	
	public function sendUpdateOrder($orderid,$type="shop"){
		require_once "extends/pushtpl.class.php";
		$order=M("mod_mmjz_order")->selectRow("orderid=".$orderid);
		$order["status_name"]=MM("mmjz","mmjz_order")->getStatus($order);
		if($type=="shop"){
			$open=M("mod_mmjz_shop_openbind")->selectRow("shopid=".SHOPID." AND k='weixin' ");
			if(empty($open)){
				return false;
			}
			$od=str2arr($open["content"]);
			$openid=$od["openid"];
		}else{
			$u=M("openlogin")->selectRow("userid=".$order["userid"]." AND xfrom='weixin'" );
			if(empty($u)) return false;
			$openid=$u["openid"];
		}
		$url=HTTP_HOST."/module.php?m=mmjz_order&a=show&orderid=".$orderid;
		if($order["status"]==1){
			$content="商家确认订单了";
		}elseif($order["status"]==2){
			$content="商家发货了";
		}elseif($order["status"]==3){
			$url=HTTP_HOST."/moduleshop.php?m=mmjz_order&a=show&orderid=".$orderid;
			if($order["israty"]){
				$content="用户给您评价了";				
			}else{
				$content="用户确认收货了";				
			}			
		}
		$tpl=pushTpl::wxList("updateorder");
		if($token=get_weixin_access_token()){
			$ops=array(
				"touser"=>$openid,
				"template_id"=>$tpl["key"],
				"url"=>$url,
				"data"=>array(
					"first"=>array("value"=>"订单更新通知"),
					"keyword1"=>array("value"=>$order["orderno"]),
					"keyword2"=>array("value"=>$order["status_name"]),
					"remark"=>array("value"=>$content),
				)
			);
			wx_mb_send($token["access_token"],$ops);
			return true;
		}
		return false;
	}
	
}
?>