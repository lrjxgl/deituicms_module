<?php
class flk_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$shop=M("mod_flk_shop")->selectRow("shopid=".SHOPID);
		$site_city=M("site_city")->children(0);
		$shop["trueimgurl"]=images_site($shop["imgurl"]);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"site_city"=>$site_city
		));
		$this->smarty->display("flk_shop/index.html");
	}
	
	public function onSave(){
		$data=M("mod_flk_shop")->postData();
		M("mod_flk_shop")->update($data,"shopid=".SHOPID);
		$this->goAll("保存成功");
	}
	
}
?>