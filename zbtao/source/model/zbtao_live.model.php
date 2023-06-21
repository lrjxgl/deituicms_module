<?php
class zbtao_liveModel extends model{
	public $table="mod_zbtao_live";
	public function Dselect($ops,&$rscount=false){
		$list=$this->select($ops,$rscount);
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		} 
		return $list;
	}
	
	public function getByUserid($userid){
		$row=$this->where("userid=?")->row($userid);
		return $row;
	}
	
}