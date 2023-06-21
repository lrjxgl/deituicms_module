<?php
class f2c_doneModel extends model{
	public $table="mod_f2c_done";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			foreach($res as $rs){
				$teamids[]=$rs["teamid"];
				$scids[]=$rs["scid"];
			}
			$teams=MM("f2c","f2c_team")->getListByIds($teamids);
			
			foreach($res as $k=>$v){
				$v["team_nickname"]=$teams[$v["teamid"]]["nickname"];
				
				$res[$k]=$v;
			}
		}
		return $res;
	}
	/**
	 * option=array(
		"smonth"=>"201903",
		"teamid"=>1,
		"money"=>10
	 )
	 */
	
	public function add($ops){
		$row=M("mod_f2c_done")->selectRow("teamid=".$ops["teamid"]." AND smonth=".$ops["smonth"]);
		if($row){
			M("mod_f2c_done")->update(array(
				"money"=>$row["money"]+$ops["money"]
			),"id=".$row["id"]);
		}else{
			M("mod_f2c_done")->insert(array(
				"teamid"=>$ops["teamid"],
				"smonth"=>$ops["smonth"],
				"money"=>$ops["money"],
				"income"=>$ops["income"],
				"createtime"=>$ops["createtime"]
			));
		}
	}
}