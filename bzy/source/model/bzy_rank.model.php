<?php
class bzy_rankModel extends model{
	public $table="mod_bzy_rank";
	public function get($eventid,$userid){
		$row=$this->selectRow("eventid=".$eventid." AND userid=".$userid);
		if(empty($row)){
			$this->insert([
				"userid"=>$userid,
				"eventid"=>$eventid
			]);
			$row=$this->selectRow("eventid=".$eventid." AND userid=".$userid);
		}
		return $row;
	}
	
	public function rank($eventid,$limit){
		$res=$this->select(array(
			"where"=>" eventid=".$eventid,
			"order"=>" grade DESC",
			"limit"=>$limit
		));
		if(!empty($res)){
			$uids=[];
			foreach($res as $rs){
				$uids[]=$rs["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($res as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$res[$k]=$v;
			}
			return $res;
		}
		return [];
	}
	
	public function stat($eventid,$userid,$grade){
		$row=$this->selectRow(" userid=".$userid." AND eventid=".$eventid);
		if(empty($row)){
			$this->insert([
				"userid"=>$userid,
				"eventid"=>$eventid,
				"grade"=>$grade
			]);
		}else{
			$this->update([
				"grade"=>$row["grade"]+$grade
			],"id=".$row["id"]);
		}
	}
	
}