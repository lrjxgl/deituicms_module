<?php
class fishing_free_accountModel extends model{
	public $table="mod_fishing_free_account";
	public function get($placeid){
		$row=$this->selectRow("placeid=".$placeid);
		if(empty($row)){
			$this->insert(array(
				"placeid"=>$placeid,
				"createtime"=>date("Y-m-d H:i:s")
			));
			$row=$this->selectRow("placeid=".$placeid);
		}
		return $row;
	}
	public function Add($ops){
		$row=$this->selectRow("placeid=".$placeid);
		$ops["income"]=0;
		if($ops["money"]>0){
			$ops["income"]=$ops["money"];
		}
		$this->update(array(
			"money"=>$row["money"]+$ops["money"],
			"income"=>$row["income"]+$ops["income"]
		),"placeid=".$placeid);
		$content=$ops["content"]."之前".$row["money"]."元，现在".($row["money"]+$ops["money"])."元";
		M("mod_fishing_free_account_log")->insert(array(
			"placeid"=>$placeid,
			"money"=>$ops["money"],
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>$content
		));
	}
	
}