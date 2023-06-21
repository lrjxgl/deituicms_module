<?php
class gread_backcartModel extends model{
	public $table="mod_gread_backcart";
	public function __construct(){
		parent::__construct();
	}
	
	public function getBooks($userid,$shopid=0){
		$where=" userid=".$userid;
		if($shopid){
			$where.=" AND shopid=".$shopid;
		}
		$data=$this->select(array(
			"where"=>$where
		));
		$res=array();
		if($data){
			foreach($data as $v){
				$res[$v['bookid']]=1;
			}
			return $res;
		}
	}
}
?>