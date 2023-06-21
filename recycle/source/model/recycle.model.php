<?php
class recycleModel extends model{
	public $table="mod_recycle";
	public function getShopid(){
		if($shopid=get_post("shopid","i")){
			return $shopid;
		}elseif(isset($_GET["recycle_shopid"])){
			return intval($_GET["recycle_shopid"]);
		}elseif($_SESSION["recycle_shopid"]){
			return intval($_session["recycle_shopid"]);
		}elseif($_COOKIE["recycle_shopid"]){
			return intval($_COOKIE["recycle_shopid"]);
		}else{
			return 0;
		}
	}
	
	public function statusList(){
		$statusList=array(
			0=>"未接单",
			1=>"处理中",
			2=>"取货中",
			3=>"已完成",
			4=>"已取消"
		);
		return $statusList;
	}
	
}