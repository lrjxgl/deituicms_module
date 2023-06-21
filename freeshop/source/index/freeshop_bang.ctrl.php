<?php 
class freeshop_bangControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("freeshop_bang/index.html");
	}
	
	public function onShop(){
		$list=MM("freeshop","freeshop_shop")->Dselect(array(
			"where"=>" status=1",
			"order"=>" follow_num DESC ",
			"limit"=>99,
			"fields"=>"shopid,shopname,follow_num,imgurl"
		));
		$this->goAll("success",0,array("list"=>$list));
	}
	
	public function onProduct(){
		$list=MM("freeshop","freeshop_product")->Dselect(array(
			"where"=>" status=1",
			"order"=>" buynum DESC ",
			"limit"=>99,
			"fields"=>"productid,userid,shopid,content,imgurl,imgsdata,price,buynum"
		));
		$this->goAll("success",0,array("list"=>$list));
	}
}