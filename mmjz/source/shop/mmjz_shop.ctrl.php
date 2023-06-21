<?php
class mmjz_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$shop=M("mod_mmjz_shop")->selectRow("shopid=".SHOPID);
		$shop["true_imgurl"]=images_site($shop["imgurl"]);
		$shop["true_banner"]=images_site($shop["banner"]);
		$site_city=M("site_city")->children(0);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"site_city"=>$site_city
		));
		$this->smarty->display("mmjz_shop/index.html");
	}
	
	public function onSave(){
		$data=M("mod_mmjz_shop")->postData();
		M("mod_mmjz_shop")->update($data,"shopid=".SHOPID);
		$this->goAll("保存成功");
	}
	
}
?>