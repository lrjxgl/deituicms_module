<?php
class household_sender_rankControl extends skymvc{
	
	public function onDefault(){
		$rank=MM("household","household_sender_rank")->get(SENDERID);
		$logList=M("mod_household_sender_rank_log")->select(array(
			"where"=>" senderid=".SENDERID,
			"order"=>"id DESC"
		));
		if($logList){
			foreach($logList as $v){
				$ids[]=$v["rankid"];
			}
			$ranks=MM("household","household_rank")->getListByIds($ids);
			foreach($logList as $k=>$v){
				$v["title"]=$rank[$v["rankid"]]["title"];
				$logList[$k]=$v;
				
			}
		}
		$this->smarty->goAssign(array(
			"rank"=>$rank,
			"logList"=>$logList
		));
		$this->smarty->display("household_sender_rank/index.html");
	}
	
}