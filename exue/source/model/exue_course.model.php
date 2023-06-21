<?php
class exue_courseModel extends model{
	public $table="mod_exue_course";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=parent::select($option,$rscount);
		if($res){
			foreach($res as $rs){
				$shopids[]=$rs["shopid"];
			}
			$shops=MM("exue","exue_shop")->getListByIds($shopids);
			 
			foreach($res as $k=>$rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$rs["shop"]=$shops[$rs["shopid"]];
				$res[$k]=$rs;
			}
		}
		return $res;
	}
	
	public function getListByIds($ids,$fields="*"){
		$res=$this->Dselect(array(
			"where"=>" courseid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["courseid"]]=$rs;
			}
		}
		return $list;
	}
	
}