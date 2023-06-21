<?php
class taoke_searchcacheModel extends model{
	public $table="mod_taoke_searchcache";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,&$rscount=false){
		$list=$this->select($option,$rscount);
		if($list){
			foreach($list as $k=>$v){
				$v=str2arr($v["content"]);
				$list[$k]=$v;
			}
		}
		return $list;
	}
	
}