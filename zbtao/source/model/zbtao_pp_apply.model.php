<?php
class zbtao_pp_applyModel extends model{
	public $table="mod_zbtao_pp_apply";
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
	public function get($ppid,$fields="*"){
		$row=$this->where("ppid=?")->field($fields)->row($ppid);
		if(!empty($row)){
			$row["imgurl"]=images_site($row["imgurl"]);
		}
		return $row;
	}
	
}