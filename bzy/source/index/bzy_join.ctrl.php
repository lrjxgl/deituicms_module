<?php
class bzy_joinControl extends skymvc{
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		$where=" userid=".$userid." ";
		 
		$url="/module.php?m=bzy_join&a=my";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_bzy_event_join_stat")->select($option,$rscount);
		
		if($data){
			$ids=[];
			foreach($data as $v){
				$ids[]=$v["eventid"];
			}
			$ps=MM("bzy","bzy_event")->getListByIds($ids);
			foreach($data as $k=>$v){
				
				$data[$k]=$ps[$v["eventid"]];
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
		$this->smarty->display("bzy_join/my.html");
	}
}