<?php
class bzy_eventControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onList(){
		$type=get("type","h");
		$time=time();
		$order=" orderindex ASC ";
		switch($type){
			case "unbegin":
				$where=" status=1 AND stime>".$time."  ";
				break;
			case "finish":
				$where=" status=1 AND etime<".$time."  ";
				break;
			default:
				$where=" status=1 AND stime<".$time." AND etime>".$time." ";
				
				break;
		}
		$start=get("start","i");
		$limit=12;
		$rscount=true;
		$list=MM("bzy","bzy_event")->select(array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit,
		),$rscount);
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
			"type"=>$type
		));
	}
	
	public function onShow(){
		 
		$userid=M("login")->userid;
		$eventid=get("eventid","i");
		$event=M("mod_bzy_event")->selectRow("eventid=".$eventid);
		$event["imgurl"]=images_site($event["imgurl"]);
		$event["banner"]=images_site($event["banner"]);
		//join
		$join=[];
		if($userid){
			$day=date("Y-m-d");
			$join=MM("bzy","bzy_event_join")->getUserByDay($userid,$eventid,$day);
			
		}
		
		$this->smarty->goassign(array(
			"event"=>$event,
			"join"=>$join
		));
		$this->smarty->display("bzy_event/show.html");
	}
	public function CacheGetJoin($key){
		$dir="temp/bzy/".$this->getDir($key);
		$file=$dir."/".$key.".txt";
		if(!file_exists($file)){
			return false;
		}
		return unserialize(file_get_contents($file));
		 
	}
	public function getDir($key){
		$md5=md5($key);
		return $md5[0]."/".$md5[1]."/".$md5[2];
	}
	public function CacheSetJoin($key,$val){
		$dir="temp/bzy/".$this->getDir($key);
		umkdir($dir);
		$file=$dir."/".$key.".txt";
		file_put_contents($file,serialize($val));
	}
	public function onPlay(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$eventid=get("eventid","i");
		$event=M("mod_bzy_event")->selectRow("eventid=".$eventid);
		$day=date("Y-m-d");
		$dtime=str_replace("-","",$day);
		$r=M("mod_bzy_event_join_stat")->selectRow("userid=".$userid." AND eventid=".$eventid);
		if(!$r){
			M("mod_bzy_event_join_stat")->insert(array(
				"userid"=>$userid,
				"eventid"=>$eventid,
				"dateline"=>time()
			));
		}
		$res=M("mod_bzy_event_join")->selectRow("userid=".$userid." AND eventid=".$eventid." AND dtime='".$day."' ");
		if(empty($res)){
			
			M("mod_bzy_event_join")->insert(array(
				"userid"=>$userid,
				"eventid"=>$eventid,
				"max_num"=>$event["limit_num"],
				"dtime"=>$day
			));
			$res=M("mod_bzy_event_join")->selectRow("userid=".$userid." AND eventid=".$eventid." AND dtime='".$day."' ");
		}else{
			if($res["max_num"]<=$res["use_num"]){
				$this->goAll("今日已用完".$res["max_num"]."博饼次数",1);
			}
			
		}
		
		$arr=[];
		for($i=1;$i<=6;$i++){
			$arr[]=rand(1,6);
		}
		$barr=$this->parseBB($arr);
		$grade=$res["grade"]+$barr["grade"];
		 
		M("mod_bzy_event_join")->update(array(
			"use_num"=>$res["use_num"]+1,
			"grade"=>$grade
		),"id=".$res["id"]);
		MM("bzy","bzy_rank")->stat($eventid,$userid,$barr["grade"]);
		 
		if($barr["grade"]>0){
			$msg="恭喜，你博中了".$barr["name"];
		}else{
			$msg="继续加油";
		}
		//log
		MM("bzy","bzy_event_log")->add(array(
			"userid"=>$userid,
			"eventid"=>$eventid,
			"num"=>implode(",",$arr),
			"res"=>arr2str($barr),
			"dateline"=>time()
		));
		echo json_encode(array(
			"error"=>0,
			"message"=>$msg,
			"rands"=>$arr,
			"result"=>$barr,
			"grade"=>$grade,
			"has_num"=>$res["max_num"]-$res["use_num"]-1
		));
	}
	
	public function onPaihang(){
		
	}
	
	/*博饼解析*/
	public function parseBB($arr){
		sort($arr);
		$cts=[];
		foreach($arr as $i){
			if(isset($cts[$i])){
				$cts[$i]++;
			}else{
				$cts[$i]=1;
			}	
		}
		sort($cts);
		 
		$max=$cts[count($cts)-1];
		 
		$str=implode("",$arr);
		
		/*
		1 秀才 2 举人 4进士
		8 三红 16 对堂 
		32 状元 
		64 状元插金花
		*/
		switch($max){
			case 1://顺子
				return ["grade"=>16,"name"=>"对堂"];
				break;
			case 6://六补
				if($str=="444444"){
					return ["grade"=>64,"name"=>"六朴红"];
				}else{
					return ["grade"=>32,"name"=>"六朴黑"];
				}
				break;
			case 5://五个
				$keys=array_keys($cts);
				if($keys[1]==4){
					return ["grade"=>64,"name"=>"五状元带".$keys[0]];
				}else{
					return ["grade"=>64,"name"=>"五状元"];
				}
				break;
			case 4://4个
				$keys=array_keys($cts);
				if($str=="114444"){
					return ["grade"=>128,"name"=>"状元插金花"];
				}elseif($key[2]==4){
					return ["grade"=>64,"name"=>"状元带".($keys[0]+$key[1])];
				}else{
					return ["grade"=>4,"name"=>"四进"];
				}
			case 3://三红
				$num=substr_count($str,"4");
				if($num==3){
					return ["grade"=>8,"name"=>"三红"];	
				}elseif($num==2){
					return ["grade"=>2,"name"=>"举人"];
				}elseif($num==1){
					return ["grade"=>1,"name"=>"秀才"];
				}
				break;
			default:
				$num=substr_count($str,"4");
				if($num==2){
					return ["grade"=>2,"name"=>"举人"];
				}elseif($num==1){
					return ["grade"=>1,"name"=>"秀才"];
				}
				break;
				
		}
		return ["grade"=>0,"name"=>"落榜"];
	}
	
}