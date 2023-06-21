<?php
class b2b_shop_earnestModel extends model{
	
	public $table="mod_b2b_shop_earnest";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid){
		$row=$this->selectRow("shopid=".$shopid);
		if(!$row){
			$this->insert(array(
				"shopid"=>$shopid,
				"createtime"=>date("Y-m-d H:i:s"),
				"money"=>0
			));
			$row=$this->selectRow("shopid=".$shopid);
		}
		return $row;
	}
	
	public function addMoney($option){
		$shopid=intval($option['shopid']);
		$money=intval($option['money']);
		$row=$this->get($shopid);
		$newMoney=$row['money']+$money;
		$time=date("Y-m-d H:i:s");
		if(!isset($option["content"])){
			$content="您支付了{$money}元，";
		}else{
			$content=$option["content"].",";
		}
		$content.="原来{$row['money']},现在{$newMoney}元";
		 
		M("mod_b2b_shop")->update(array(
			"earnest"=>$newMoney
		),"shopid=".$shopid);
		$this->update(array(
			"money"=>$newMoney,
			"lasttime"=>$time,
		),"shopid=".$shopid);
		M("mod_b2b_shop_earnest_log")->insert(array(
			"shopid"=>$shopid,
			"createtime"=>$time,
			"money"=>$money,
			"content"=>$content
		));
		 
	}
	
}
?>