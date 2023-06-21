<?php
class zhuliModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_zhuli";
	}
	public function Dselect($ops,&$rscount=false){
		$list=$this->select($ops,$rscount);
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		return $list;
	}
	public function getListByIds($ids){
		$d=$this->select(array(
			"where"=>" id in("._implode($ids).") "
		));
		$data=array();
		if($d){
			foreach($d as $v){
				$data[$v['id']]=$v;
			}
			return $data;
		}
	}
}

?>