<?php
class pdd_pintuanControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	
	public function onInvite(){
		$orderid=get("orderid","i");
		$order=MM("pdd","pdd_order")->selectRow("orderid=".$orderid);
		//获取拼团人数
		$need_num=0;
		$pt_ewm="";
		if($order["ispin"]){
			$product=MM("pdd","pdd_product")->selectRow(array(
				"where"=>"id=".$order["productid"],
				"fields"=>"pt_open,pt_min"
			));
			if($order["pin_orderid"]){
				$ptorderid=$order["pin_orderid"];
				$ptorder=MM("pdd","pdd_order")->selectRow("orderid=".$order["pin_orderid"]);
				$need_num=$product["pt_min"]-$ptorder["pin_num"];
			}else{
				$need_num=$product["pt_min"]-$order["pin_num"];
				$ptorderid=$orderid;
			}
			
			$pturl=HTTP_HOST."/module.php?m=pdd_product&a=show&id=".$order["productid"]."&orderid=".$ptorderid;
			$pt_ewm=HTTP_HOST."/index.php?m=qrcode&content=".urlencode($pturl)."&title=".urlencode("快来和我一起拼团吧");
		}
		
		$this->smarty->goAssign(array(
			"need_num"=>$need_num,
			"order"=>$order,
			"pt_ewm"=>$pt_ewm
		));
		$this->smarty->display("pdd_pintuan/invite.html");
	}
}