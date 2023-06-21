<?php
class shopsite_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	public function onShow(){
		$shopid=SHOPID;
		$shop=MM("b2b","b2b_shop")->get($shopid);
		$id=get("id","i");
		$data=M("mod_b2b_product")->selectRow("id=".$id);
		$data["imgurl"]=images_site($data["imgurl"]);
		$data["content"]=M("mod_b2b_product_data")->selectOne(array(
			"where"=>"id=".$id,
			"fields"=>"content"
		));
		$this->smarty->goAssign(array(
			"data"=>$data,
			"shop"=>$shop
		));
		$this->smarty->display("index/product_show.html");
	}
	
}
?>