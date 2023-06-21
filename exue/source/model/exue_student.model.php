<?php
class exue_studentModel extends model{
	public $table="mod_exue_student";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=parent::select($option,$rscount);
		if($res){
			foreach($res as $k=>$rs){
				$rs["gender_title"]=$rs["gender"]==1?"男":"女";
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$res[$k]=$rs;
			}
		}
		return $res;
	}
	public function get($stid,$fields="*"){
		$row=$this->selectRow(array(
			"where"=>"stid=".$stid,
			"fields"=>$fields
		));
		if($row){
			$row["imgurl"]=images_site($row["imgurl"]);
			$row["gender_title"]=$row["gender"]==1?"男":"女";
		}
		return $row;
	}
	public function getListByIds($ids,$fields="*"){
		$res=$this->Dselect(array(
			"where"=>" stid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["stid"]]=$rs;
			}
		}
		return $list;
	}
	
}