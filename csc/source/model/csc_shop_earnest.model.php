<?php
class csc_shop_earnestModel extends model{
	
	public $table="mod_csc_shop_earnest";
	public function __construct(&$base=null){
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
		$this->begin();
		M("mod_csc_shop")->update(array(
			"earnest"=>$newMoney
		),"shopid=".$shopid);
		$this->update(array(
			"money"=>$newMoney,
			"lasttime"=>$time,
		),"shopid=".$shopid);
		M("mod_csc_shop_earnest_log")->insert(array(
			"shopid"=>$shopid,
			"createtime"=>$time,
			"money"=>$money,
			"content"=>"您支付了{$money}元，原来{$row['money']},现在{$newMoney}元"
		));
		$this->commit();
	}
	
}
?>