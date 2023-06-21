<?php
class gread_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_gread_shop")->selectRow("shopid=".SHOPID);
		$shop["trueimgurl"]=images_site($shop["imgurl"]);
		$this->smarty->goassign(array(
			"shop"=>$shop
		));
		$this->smarty->display("gread_shop/index.html");
	}
	
	public function onSave(){
		$data=M("mod_gread_shop")->postData();
		M("mod_gread_shop")->update($data,"shopid=".SHOPID);
		$this->goAll("保存成功");
	}
	
}
?>