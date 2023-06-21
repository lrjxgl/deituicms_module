<?php
class taoke_user_moneyModel extends model{
	
	public $table="mod_taoke_user_money";
	public function __construct(){
		parent::__construct();
	}
	public function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(!$row){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=$this->selectRow("userid=".$userid);
		}
		return $row;
	}
	/**
	 * income 收入
	 * balance 可用余额
	 */
	public function addMoney($ops){
		$userid=intval($ops['userid']);
		$ops["income"]=floatval($ops["income"]);
		$ops["balance"]=floatval($ops["balance"]);
		$row=$this->get($userid);
		$this->begin();
		$time=date("Y-m-d H:i:s");
		$bc=$ops["content"].",之前".$row["balance"].",现在".($row["balance"]+$ops["balance"]);
		$this->update(array(
			"income"=>$row["incomde"]+$ops["income"],
			"balance"=>$row["balance"]+$ops["balance"]
		),"userid=".$userid);
		M("mod_taoke_user_money_log")->insert(array(
			"userid"=>$userid,
			"createtime"=>$time,
			"money"=>$ops["balance"],
			"content"=>$bc
		));
		$this->commit();
	}
	
}
?>