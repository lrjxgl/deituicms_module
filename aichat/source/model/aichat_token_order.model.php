<?php
class aichat_token_orderModel extends model{
	public $table="mod_aichat_token_order";
	
	public function buySuccess($data=[]){
		$orderid=$data["orderid"];
		$row=$this->selectRow("orderid=".$orderid);
		if(empty($row) || $row["ispay"]==1){
			return false;
		}
		$this->update(array(
			"ispay"=>1,
			"recharge_id"=>$data["recharge_id"],
			"paytype"=>$data["paytype"]
		),"orderid=".$orderid);
		//变更账户
		MM("aichat","aichat_user")->addToken(array(
			"userid"=>$row["userid"],
			"num"=>$row["num"],
			"content"=>"你花了".$row["money"]."元，购买了".$row["num"]."个token"
		));
	}
	
}