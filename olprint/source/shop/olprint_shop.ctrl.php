<?php
class olprint_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$shop=M("mod_olprint_shop")->selectRow("shopid=".SHOPID);
		$site_city=M("site_city")->children(0);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"site_city"=>$site_city
		));
		$this->smarty->display("olprint_shop/index.html");
	}
	
	public function onSave(){
		$data=M("mod_olprint_shop")->postData();
		M("mod_olprint_shop")->update($data,"shopid=".SHOPID);
		$this->goAll("保存成功");
	}
	
}
?>