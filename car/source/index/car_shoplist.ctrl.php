<?php
class car_shoplistControl extends skymvc{
	 
	public function __construct(){
		parent::__construct();
	}
	 
	public function onDefault(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND shopname like '%".$keyword."%' ";
			$url.="&keyword=".urlencode($keyword);
		}
		
		$list=MM("car","car_shop")->Dselect(array(
			"where"=>$where
		)); 
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"keyword"=>$keyword
			 
		));
		$this->smarty->display("car_shoplist/index.html");
	}
	
	 
	
}
?>