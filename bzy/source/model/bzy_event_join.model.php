<?php
class bzy_event_joinModel extends model{
	public $table="mod_bzy_event_join";
	public function getUserByDay($userid,$eventid,$day){
		$res=M("mod_bzy_event_join")->selectRow("userid=".$userid." AND eventid=".$eventid." AND dtime='".$day."' ");
		if(empty($res)){
			$event=M("mod_bzy_event")->selectRow("eventid=".$eventid);
			$this->insert(array(
				"userid"=>$userid,
				"eventid"=>$eventid,
				"max_num"=>$event["limit_num"],
				"dtime"=>$day
			));
			$res=$this->selectRow("userid=".$userid." AND eventid=".$eventid." AND dtime='".$day."' ");
		}
		return $res;
	}
	public function rank($eventid,$day,$limit=100){
		
		$res=$this->select(array(
			"where"=>" eventid=".$eventid." AND dtime='".$day."' ",
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
}