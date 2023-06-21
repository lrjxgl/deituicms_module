<?php
class freeshop_shop_moneyModel extends model{
	public $table="mod_freeshop_shop_money";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid){
		$row=$this->selectRow("shopid=".$shopid);
		if(empty($row)){
			$this->insert(array(
				"shopid"=>$shopid
			));
			$row=$this->selectRow("shopid=".$shopid);
		}
		return $row;
	}
	
	/**
	 * income 收入
	 * balance 可用余额
	 */
	public function addMoney($ops){
		$shopid=intval($ops['shopid']);
		$ops["income"]=floatval($ops["income"]);
		$ops["balance"]=floatval($ops["balance"]);
		$row=$this->get($shopid);
		$this->begin();
		$time=date("Y-m-d H:i:s");
		$bc=$ops["content"].",之前".$row["balance"]."元,现在".($row["balance"]+$ops["balance"])."元";
		$this->update(array(
			"income"=>$row["income"]+$ops["income"],
			"balance"=>$row["balance"]+$ops["balance"]
		),"shopid=".$shopid);
		M("mod_freeshop_shop_money_log")->insert(array(
			"shopid"=>$shopid,
			"createtime"=>$time,
			"money"=>$ops["balance"],
			"content"=>$bc
		));
		$this->commit();
	}
	
}