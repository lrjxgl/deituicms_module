<?php
class flk_daxinModel extends model{
	public $table="mod_flk_daxin";
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
	
	public function add($ops){
		$userid=$ops["userid"];
		$row=$this->get($userid);
		$this->update(array(
			"money"=>$row["money"]+$ops["money"]
		),"userid=".$userid);
		if($ops["money"]<0){
			$content="消耗了".$ops["money"]."元打新券，之前".$row["money"]."元,现在".($row["money"]+$ops["money"])."元";
		}else{
			$content="增加了".$ops["money"]."元打新券，之前".$row["money"]."元,现在".($row["money"]+$ops["money"])."元";
		}
		M("mod_flk_daxin_log")->insert(array(
			"userid"=>$userid,
			"money"=>$ops["money"],
			"content"=>$content,
			"dateline"=>time()
			
		));
		
	}
	
}