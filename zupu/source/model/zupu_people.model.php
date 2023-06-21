<?php
class zupu_peopleModel extends model{
	public $table="mod_zupu_people";
	public $maxstep=3;
	public function __construct(){
		parent::__construct();
	}
	public function children($gid=0,$pid=0,$step=0){
		$list=$this->select(array(
			"where"=>" pid=".$pid." AND gid=".$gid,
			"order"=>" age DESC"
		));
		$step++;
		if($step>$this->maxstep){
			//return false;
		}
		if($list){
			foreach($list as $k=>$v){
				$v["child"]=$this->children($gid,$v["id"],$step);
				$list[$k]=$v;
			}
		}
		return $list;
	}
	
}