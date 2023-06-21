<?php
class bzy_event_logControl extends skymvc{
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		$where=" userid=".$userid." ";
		 
		$url="/module.php?m=bzy_event_log&a=my";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("bzy","bzy_event_log")->select($option,$rscount);
		
		if($data){
			$ids=[];
			foreach($data as $v){
				$ids[]=$v["eventid"];
			}
			$ps=MM("bzy","bzy_event")->getListByIds($ids);
			foreach($data as $k=>$v){
				$v["title"]=$ps[$v["eventid"]]["title"];
				$v["res"]=str2arr($v["res"]);
				$data[$k]=$v;
			}
		}
		 
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"tablename"=>$tablename,
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		$this->smarty->display("bzy_event_log/my.html");
	}
}