<?php
class s2c_teamControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		
		$this->smarty->display("s2c_team/index.html");
	}
	public function onData(){
		//社区
		$team=MM("s2c","s2c_team")->selectRow("teamid=".TEAMID);
		if($team){
			$team["userhead"]=images_site($team["userhead"]);
		} 
		$where=" status=1 ";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND title like '%".$keyword."%' ";
		} 
		 
		$list=MM("s2c","s2c_team")->select(array(
			"where"=>$where
		));
		$gps=gps_get();
		$lat=$gps['lat'];
		$lng=$gps['lng'];
		 
		if($list){
			 
			foreach($list as $k=>$v){
				 
				$v["userhead"]=images_site($v["userhead"]);
				$v["imgurl"]=images_site($v['imgurl']);
				$dis=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
				$distance[$k]=$v['distance']=$dis/1000;
				$teamids[$k]=$v['teamid'];
				$list[$k]=$v;
			}
			array_multisort ( $distance ,  SORT_ASC ,  $teamids ,  SORT_ASC ,  $list );
		}
		$rdata=array(
			"list"=>$list,
			"team"=>$team
		);
		$this->goAll("success",0,$rdata);
	}
	public function onSet(){
		$teamid=get("teamid","i");
		setCookie("s2c_teamid",$teamid,time()+3600*24*1000);
		echo json_encode(array(
			"error"=>0
		));
	}
}