<?php
class fxa_userModel extends model{
	public $table="mod_fxa_user";
	public function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(empty($row)){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=$this->selectRow("userid=".$userid);
		}
		return $row;
	}
	public function addMoney($ops){
		$u=$this->get($ops["userid"]);
		$userid=$ops["userid"];
		$money=$u["money"]+$ops["money"];
		if($ops["money"]>0){
			$content="收入".$money."元，";
			$total_money=$u["total_money"]+$ops["money"];
		}else{
			$content="提现".$money."元";
			$total_money=$u["total_money"];
		}
		$this->update(array(
			"money"=>$money,
			"total_money"=>$total_money
		),"userid=".$u["userid"]);
		
		$content.="原来".$u["money"]."元，现在".$money."元";
		M("mod_fxa_log")->insert(array(
			"userid"=>$userid,
			"content"=>$content,
			"money"=>$money,
			"dateline"=>time()
		));
	}
	
}