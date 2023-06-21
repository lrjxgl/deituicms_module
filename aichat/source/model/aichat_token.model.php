<?php
class aichat_tokenModel extends model{
	public $table="mod_aichat_token";
	public function buyToken($userid,$orderid){
		$aiuser=MM("aichat","aichat_user")->get($userid);
		$row=M("mod_aichat_token_order")->selectRow("orderid=".$orderid);
		MM("aichat","aichat_user")->addToken(array(
			"userid"=>$userid,
			"num"=>$row["num"]
		));
	}
}