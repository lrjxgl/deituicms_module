<?php
class house_peitaoModel extends model{
	
	public $table="mod_house_peitao";
	public function __construct(){
		parent::__construct();
	}
	public function typeList(){
		$ops=array(
			1=>"交通",
			2=>"学校",
			3=>"餐饮",
			4=>"购物",
			5=>"娱乐",
			6=>"医院"
		);
		return $ops;
	}
	public function Dselect($option,&$rscount=false){
		$typeList=$this->typeList();
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['typeid_title']=$typeList[$v["typeid"]];
				$data[$k]=$v;
			}
		}
		return $data;
	}
}
?>