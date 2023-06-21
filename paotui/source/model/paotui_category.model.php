<?php
class paotui_categoryModel extends model{
	public $table="mod_paotui_category";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,&$rscount=false){
		$list=$this->select($option,$rscount);
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		return $list;
	}
	
	public function catlist(){
		$res=$this->select($option);
		$list=array();
		foreach($res as $rs){
			$list[$rs["catid"]]=$rs;
		}
		return $list;
	}
	
}
?>