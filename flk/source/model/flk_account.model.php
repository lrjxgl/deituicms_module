<?php
class flk_accountModel extends model{
	public $table="mod_flk_account";
	public function __construct(){
		parent::__construct();
	}
	public function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(empty($row)){
			$this->insert(array(
				"userid"=>$userid,
				"money"=>0
			));
			return 0;
		}
		return $row["money"];
	}
	public function addMoney($ops){
		$row=$this->selectRow("userid=".$ops["userid"]);
		if(empty($row)){
			$this->insert(array(
				"userid"=>$ops["userid"],
				"money"=>$ops["money"]
			));
		}else{
			$this->update(array(
				"money"=>$row["money"]+$ops["money"]
			),"id=".$row["id"]);
		}
		M("mod_flk_account_log")->insert(array(
			"userid"=>$ops["userid"],
			"money"=>$ops["money"],
			"content"=>$ops["content"],
			"dateline"=>time()
		));
	}
}