<?php
class s2c_teamModel extends model{
	public $table="mod_s2c_team";
	public function __construct(){
		parent::__construct();
	}
	public function get($teamid,$fields="*"){
		$row=$this->selectRow(array(
			"where"=>" teamid=".$teamid,
			"fields"=>$fields
		));
		if($row){
			$row["userhead"]=images_site($row["userhead"]);
		}
		
		return $row;
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$rss=$this->select(array(
			"where"=>" teamid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				$rs["userhead"]=images_site($rs["userhead"]);
				$data[$rs["teamid"]]=$rs;
				 
			}
			return $data;
		}
	}
	/**
	*账户变动
	**/
	public function addMoney($ops){
		
		$teamid=$ops["teamid"];
		$team=MM("s2c","s2c_team")->selectRow("teamid=".$teamid);
		if($ops["money"]>0){
			$typeid=1;
		}else{
			$typeid=2;
		}
		$up=array(
			"money"=>$team["money"]+$ops["money"]
		);
		if($ops["money"]>0){
			$up["total_money"]=$team["total_money"]+$ops["money"];
		}
		MM("s2c","s2c_team")->update($up,"teamid=".$teamid);
		$content=$ops["content"].","."之前".$team["money"].",现在".($team["money"]+$ops["money"])."元";
		M("mod_s2c_team_log")->insert(array(
			"teamid"=>$teamid,
			"money"=>$ops["money"],
			"typeid"=>$typeid,
			"content"=>$content,
			"createtime"=>$ops["createtime"]
		));
	}
	
}