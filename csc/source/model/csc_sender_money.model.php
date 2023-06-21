<?php
class csc_sender_moneyModel extends model{
	public $table="mod_csc_sender_money";
	public function __construct(){
		parent::__construct();
	}
	public function get($senderid){
		$row=$this->selectRow("senderid=".$senderid);
		if(!$row){
			$this->insert(array(
				"senderid"=>$senderid
			));
			$row=$this->selectRow("senderid=".$senderid);
		}
		return $row;
	}
	
	/**
	 * income 收入
	 * balance 可用余额
	 */
	public function addMoney($ops){
		$senderid=intval($ops['senderid']);
		$ops["income"]=isset($ops["income"])?intval($ops["income"]):0;
		$ops["balance"]=intval($ops["balance"]);
		$row=$this->get($senderid);
		$this->begin();
		$time=date("Y-m-d H:i:s");
		$bc=$ops["content"].",之前".$row["balance"].",现在".($row["balance"]+$ops["balance"]);
		$this->update(array(
			"income"=>$row["incomde"]+$ops["income"],
			"balance"=>$row["balance"]+$ops["balance"]
		),"senderid=".$senderid);
		M("mod_csc_sender_money_log")->insert(array(
			"senderid"=>$senderid,
			"createtime"=>$time,
			"money"=>$ops["balance"],
			"content"=>$bc
		));
		$this->commit();
	}
}