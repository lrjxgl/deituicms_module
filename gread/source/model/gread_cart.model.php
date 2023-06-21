<?php
class gread_cartModel extends model{
	public $table="mod_gread_cart";
	public function __construct(){
		parent::__construct();
	}
	
	public function getBooks($userid,$shopid){
		$data=$this->select(array(
			"where"=>" userid=".$userid." AND shopid=".$shopid
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