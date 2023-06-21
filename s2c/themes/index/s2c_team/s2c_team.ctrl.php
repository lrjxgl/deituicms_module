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
		$shequ=MM("s2c","s2c_shequ")->selectRow("scid=".SCID);
		if($shequ){
			$team=MM("s2c","s2c_team")->selectRow("teamid=".$shequ["teamid"]);
			$shequ["nickname"]=$team["nickname"];
			$shequ["userhead"]=images_site($team["userhead"]);
			
		}
		$where=" status=1 AND teamid>0";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND title like '%".$keyword."%' ";
		} 
		$list=MM("s2c","s2c_shequ")->select(array(
			"where"=>$where
		));
		$gps=gps_get();
		$lat=$gps['lat'];
		$lng=$gps['lng'];
		 
		if($list){
			foreach($list as $v){
				$teamids[]=$v["teamid"];				
			}
			$teams=MM("s2c","s2c_team")->getListByIds($teamids,"teamid,nickname");
			foreach($list as $k=>$v){
				$v["nickname"]=$teams[$v["teamid"]]["nickname"];
				$v["userhead"]=$teams[$v["teamid"]]["userhead"];
				$v["imgurl"]=images_site($v['imgurl']);
				$dis=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
				$distance[$k]=$v['distance']=$dis/1000;
				$sqids[$k]=$v['sqid'];
				$list[$k]=$v;
			}
			array_multisort ( $distance ,  SORT_ASC ,  $sqids ,  SORT_ASC ,  $list );
		}
		$rdata=array(
			"list"=>$list,
			"shequ"=>$shequ
		);
		$this->goAll("success",0,$rdata);
	}
	public function onSet(){
		$scid=get("scid","i");
		setCookie("s2c_scid",$scid,time()+3600*24*1000);
		echo json_encode(array(
			"error"=>0
		));
	}
}