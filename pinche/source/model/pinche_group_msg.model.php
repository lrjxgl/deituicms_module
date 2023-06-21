<?php
class pinche_group_msgModel extends model{
	public $table="mod_pinche_group_msg";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($ops=array(),&$rscount=false){
		$res=$this->select($ops);
		if($res){
			 
			foreach($res as $rs){
				$uids[]=$rs["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($res as $k=>$v){
				 
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$list[$k]=$v;
			}
		}
		return $list;
	} 
	
}