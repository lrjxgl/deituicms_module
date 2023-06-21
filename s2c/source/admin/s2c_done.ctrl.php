<?php
class s2c_doneControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$url="/moduleadmin.php?m=s2c_done";
		$start=get("per_page","i");
		$limit=100;
		$month=date("Ym");
		$where=" smonth=".$month;
		$team_nickname=get("team_nickname","h");
		$shequ_title=get("shequ_title","h");
		if($team_nickname){
			$teamids=MM("s2c","s2c_team")->selectCols(array(
				"where"=>"nickname='".$team_nickname."'",
				"fields"=>"teamid"
			));
			 
			if(!empty($teamids)){
				$where.=" AND teamid in("._implode($teamids).") ";				
			}else{
				$where=" 1=2 ";
			}
		}
		if($shequ_title){
			$scids=MM("s2c","s2c_shequ")->selectCols(array(
				"where"=>"title='".$shequ_title."'",
				"fields"=>"scid"
			));
			if(!empty($scids)){
				$where.=" AND scid in("._implode($scids).") ";				
			}else{
				$where=" 1=2 ";
			}
		}
		$option=array(
			"where"=>$where,
			"order"=>" money DESC",
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=MM("s2c","s2c_done")->Dselect($option,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$rscount);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("s2c_done/index.html");
	}
	
	public function onLast(){
		$url="/moduleadmin.php?m=s2c_done&a=last";
		$month=date("m");
		if($month>1){
			$sm=date("Ym")-1;
		}else{
			$sm=date("Y")-1;
			$sm.="12";
		}
		$where=" smonth=".$sm;
		$team_nickname=get("team_nickname","h");
		$shequ_title=get("shequ_title","h");
		if($team_nickname){
			$teamids=MM("s2c","s2c_team")->selectCols(array(
				"where"=>"nickname='".$team_nickname."'",
				"fields"=>"teamid"
			));
			 
			if(!empty($teamids)){
				$where.=" AND teamid in("._implode($teamids).") ";				
			}else{
				$where=" 1=2 ";
			}
		}
		if($shequ_title){
			$scids=MM("s2c","s2c_shequ")->selectCols(array(
				"where"=>"title='".$shequ_title."'",
				"fields"=>"scid"
			));
			if(!empty($scids)){
				$where.=" AND scid in("._implode($scids).") ";				
			}else{
				$where=" 1=2 ";
			}
		}
		$start=get("per_page","i");
		$limit=100;
		$option=array(
			"where"=>$where,
			"order"=>" money DESC",
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=MM("s2c","s2c_done")->Dselect($option,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$rscount);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("s2c_done/last.html");
	}
	
	public function onAll(){
		$url="/moduleadmin.php?m=s2c_done&a=all";
		$start=get("per_page","i");
		$limit=100;
		$where=" 1 ";
		$month=get("month","i");
		$team_nickname=get("team_nickname","h");
		$shequ_title=get("shequ_title","h");
		if($month){
			$where.=" AND smonth=".$month;
			$url.="&month=".$month;
		}
		if($team_nickname){
			$url.="&team_nickname=".urlencode($team_nickname);
			$teamids=MM("s2c","s2c_team")->selectCols(array(
				"where"=>"nickname='".$team_nickname."'",
				"fields"=>"teamid"
			));
			 
			if(!empty($teamids)){
				$where.=" AND teamid in("._implode($teamids).") ";				
			}else{
				$where=" 1=2 ";
			}
		}
		if($shequ_title){
			$url.="&shequ_title=".urlencode($shequ_title);
			$scids=MM("s2c","s2c_shequ")->selectCols(array(
				"where"=>"title='".$shequ_title."'",
				"fields"=>"scid"
			));
			if(!empty($scids)){
				$where.=" AND scid in("._implode($scids).") ";				
			}else{
				$where=" 1=2 ";
			}
		}
		$option=array(
			"where"=>$where,
			"order"=>" smonth DESC,money DESC",
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=MM("s2c","s2c_done")->Dselect($option,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$rscount);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("s2c_done/all.html");
	}
}