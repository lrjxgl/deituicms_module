<?php
class zupu_groupModel extends model{
	public $table="mod_zupu_group";
	public function Dselect($ops=[],&$rscount=false){
		$list=$this->select($ops,$rscount);
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		return $list;
	}
}