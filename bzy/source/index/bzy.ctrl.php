<?php
class bzyControl extends skymvc{
	
	public function onDefault(){
		$list=MM("bzy","bzy_event")->select(array(
			"limit"=>3
		));
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>0
		));
		$this->smarty->display("bzy/index.html");
	}
	/**计划任务**/
	public function onCronFirst(){
		session_write_close();
		set_time_limit(0);
		$time=time();
		$daytime=3600*24;
		$eList=MM("bzy","bzy_event")
				->where(" status=1 AND etype=0 AND stime<".$time." AND etime>".($time-$daytime))
				->field("eventid,title,etype")
				->all();
		 
		if(empty($eList)){
			echo "success";
			return false;
		}
		$day=date("Y-m-d",time()-3600*24);
		//已执行的记录
		$eids=M("mod_bzy_cron_log")->where("dtime='".$day."'")->field("eventid")->cols();
		
		foreach($eList as $k=>$v){
			if(in_array($v["eventid"],$eids)){
				//continue;
			}
			$pros=MM("bzy","bzy_product")->getEventIds($v["eventid"],0);
			
			if(empty($pros)){
				continue;
			} 
			M("mod_bzy_cron_log")->insert([
				"eventid"=>$v["eventid"],
				"dtime"=>$day,
				"etype"=>0
			]);
			$list=MM("bzy","bzy_event_join")
				->where("eventid=".$v["eventid"]." AND etype=0 AND dtime='".$day."'")
				->order("grade DESC")
				->limit(0,$v["first_win_num"])
				->all();
				
				 
			if(empty($list)){
				continue;
			}
			
			if(!empty($list)){
				foreach($list as $k=>$v){
					$grades[]=$v["grade"];
				}
				array_multisort($list,$grades,SORT_ASC);
				
				foreach($list as $join){
					$productid=array_pop($pros);
					$uaddr=M("user_lastaddr")->get($join["userid"]);
					MM("bzy","bzy_order")
						->insert([
							"eventid"=>$join["eventid"],
							"joinid"=>$join["id"],
							"userid"=>$join["userid"],
							"createtime"=>date("Y-m-d H:i:s"),
							"productid"=>$productid,
							"nickname"=>$uaddr["nickname"],
							"telephone"=>$uaddr["telephone"],
							"address"=>$uaddr["address"]
						]);
				}
				
			}	
		}
		
		echo "success";
	}
}