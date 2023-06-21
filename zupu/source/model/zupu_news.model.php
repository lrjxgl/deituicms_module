<?php
class zupu_newsModel extends model{
	public $table="mod_zupu_news";
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