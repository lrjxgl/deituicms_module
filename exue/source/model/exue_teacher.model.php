<?php
class exue_teacherModel extends model{
	public $table="mod_exue_teacher";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=parent::select($option,$rscount);
		if($res){
			foreach($res as $k=>$rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$res[$k]=$rs;
			}
		}
		return $res;
	}
	public function get($tcid){
		$row=$this->selectRow("tcid=".$tcid);
		if($row){
			$row["imgurl"]=images_site($row["imgurl"]);
		}
		return $row;
	}
	public function getListByIds($ids,$fields="*"){
		$res=$this->Dselect(array(
			"where"=>" tcid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["tcid"]]=$rs;
			}
		}
		return $list;
	}
	
}