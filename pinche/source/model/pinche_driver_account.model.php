<?php
class pinche_driver_accountModel extends model{
	public $table="mod_pinche_driver_account";
	public function __construct(){
		parent::__construct();
	}
	public function get($driverid){
		$account=$this->selectRow("driverid=".$driverid);
		if(!$account){
			$this->insert(array(
				"driverid"=>$driverid
			));
			return $this->selectRow("driverid=".$driverid);
		}else{
			return $account;
		}
	}
	public function addMoney($ops){
		$driverid=intval($ops["driverid"]);
		$money=floatval($ops["money"]);
		$account=$this->get($driverid);
		
		if($money>0){
			//收入
			$typeid=1;
		}else{
			//提现
			$typeid=2;
		}
		$indata["balance_money"]=$account["balance_money"]+$money;
		if($money>0){
			$indata["total_money"]=$account["total_money"]+$money;
		}
		
		$this->update($indata,"driverid=".$driverid);
		$ops["content"].=",之前".$account["total_money"]."元，现在".($account["balance_money"]+$money)."元";
		//日志
		M("mod_pinche_driver_account_log")->insert(array(
			"driverid"=>$driverid,
			"dateline"=>time(),
			"typeid"=>$typeid,
			"content"=>$ops["content"],
			"money"=>$money
		));
	}
	
}