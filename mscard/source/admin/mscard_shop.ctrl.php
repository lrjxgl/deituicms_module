<?php
class mscard_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$where=" 1 ";
		$url="/moduleadmin.php?m=mscard_shop&a=default";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>intval(get_post('per_page')),
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_mscard_shop")->select($option,$rscount);
		 
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"data"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		
		$this->smarty->display("mscard_shop/index.html");
			
	}
	
	
	
}
?>