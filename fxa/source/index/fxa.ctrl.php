<?php
class fxaControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$list=MM("fxa","fxa_product")->Dselect(array(
			"where"=>" status=1 "
		));
		$id=get("id","i");
		if(!$id){
			$id=intval($_COOKIE["ck_fxa_id"]);
			
		}
		if($id){
			header("Location: /module.php?m=fxa_product&a=show&id=".$id);
			exit;
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("fxa/index.html");
	}
	
}

