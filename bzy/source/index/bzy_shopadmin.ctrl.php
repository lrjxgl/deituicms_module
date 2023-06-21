<?php
class bzy_shopadminControl extends skymvc{
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$eventid=4;//get("eventid","i");
		$event=M("mod_bzy_event")->selectRow("shopid=".$userid." AND eventid=".$eventid);
		if(empty($event)){
			$this->goAll("暂无权限",1);
		}
		$this->smarty->goAssign(array(
			"event"=>$event
		));
		$this->smarty->display("bzy_shopadmin/index.html");
	}
	public function onStat(){
		$eventid=get("eventid","i");
		$total_user=MM("bzy","bzy_rank")
			->where("eventid=".$eventid)
			->field("count(*) as num")
			->one();
		$total_num=MM("bzy","bzy_event_log")
			->where("eventid=".$eventid)
			->field("count(*) as num")
			->one();
		$day=date("Y-m-d");	
		$day_user=MM("bzy","bzy_event_join")
			->where("eventid=".$eventid." AND dtime='".$day."'")
			->field("count(*) as num")
			->one();
		$day_num=MM("bzy","bzy_event_log")
			->where("eventid=".$eventid." AND dtime='".$day."'")
			->field("count(*) as num")
			->one();
		$day=date("Y-m-d",time()-3600*24);
		$last_user=MM("bzy","bzy_event_join")
			->where("eventid=".$eventid." AND dtime='".$day."'")
			->field("count(*) as num")
			->one();
		$last_num=MM("bzy","bzy_event_log")
			->where("eventid=".$eventid." AND dtime='".$day."'")
			->field("count(*) as num")
			->one();
		$this->smarty->goAssign(array(
			"total_user"=>$total_user,
			"total_num"=>$total_num,
			"day_user"=>$day_user,
			"day_num"=>$day_num,
			"last_user"=>$last_user,
			"last_num"=>$last_num,
		));
	}
}
?>