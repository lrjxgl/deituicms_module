<?php
class olprint_shop_noticeModel extends model{
	public $queue=false;
	public $table="mod_olprint_shop_notice";
	public function __construct(){
		parent::__construct();
	}
	public function sendNewOrder($orderid){
		require_once "extends/pushtpl.class.php";
		$open=M("mod_olprint_shop_openbind")->selectRow("shopid=".SHOPID." AND k='weixin' ");
		if(empty($open)){
			return false;
		}
		$order=M("mod_olprint_order")->selectRow("orderid=".$orderid);
		$order["status_name"]=MM("olprint","olprint_order")->getStatus($order);
		$orderdata=MM("olprint","olprint_order_data")->get($orderid);
		$products="打印订单变更通知";
		 
		
		$od=str2arr($open["content"]);
		$tpl=pushTpl::wxList("neworder");
	 
		if($token=get_weixin_access_token()){
			$ops=array(
				"touser"=>$od["openid"],
				"template_id"=>$tpl["key"],
				"url"=>HTTP_HOST."/moduleshop.php?m=olprint_order&a=show&orderid=".$orderid,
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
		$order=M("mod_olprint_order")->selectRow("orderid=".$orderid);
		$order["status_name"]=MM("olprint","olprint_order")->getStatus($order["status"]);
		if($type=="shop"){
			$open=M("mod_olprint_shop_openbind")->selectRow("shopid=".SHOPID." AND k='weixin' ");
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
		$url=HTTP_HOST."/module.php?m=olprint_order&a=show&orderid=".$orderid;
		if($order["status"]==1){
			$content="商家确认订单了";
		}elseif($order["status"]==2){
			$content="商家发货了";
		}elseif($order["status"]==3){
			$url=HTTP_HOST."/moduleshop.php?m=olprint_order&a=show&orderid=".$orderid;
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