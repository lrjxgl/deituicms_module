<?php
class greadModel extends model{
	public $table="mod_gread";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	
	public function order_status_list(){
		$arr=array(
			0=>"未审核",
			1=>"已审核",
			2=>"配送中",
			3=>"已完成",
			11=>"已取消",
		);
		return $arr;
	}
	
	public function getShopid(){
		if(isset($_GET["shopid"])){
			return intval($_GET["shopid"]);
		}elseif(isset($_GET["gread_shopid"])){
			return intval($_GET["gread_shopid"]);
		}elseif($_SESSION["gread_shopid"]){
			return intval($_session["gread_shopid"]);
		}elseif($_COOKIE["gread_shopid"]){
			return intval($_COOKIE["gread_shopid"]);
		}else{
			return 0;
		}
	}
}
?>