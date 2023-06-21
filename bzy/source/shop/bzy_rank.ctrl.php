<?php
class bzy_rankControl extends skymvc{
	
	public function onDefault(){
		$eventid=get("eventid","i");
		$day=date("Y-m-d");
		$rankType=get("rankType","h");
		switch($rankType){
			case "lastday":
				$day=date("Y-m-d",strtotime("-1 day "));
				$list=MM("bzy","bzy_event_join")->rank($eventid,$day,100);
				break;
			case "all":
				$list=MM("bzy","bzy_rank")->rank($eventid,100);
				break;
			default:
				$list=MM("bzy","bzy_event_join")->rank($eventid,$day,100);
				break;
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		
	}
	
}