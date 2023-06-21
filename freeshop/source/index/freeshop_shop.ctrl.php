<?php
class freeshop_shopControl extends skymvc{
	public $shopid=0;
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		$userid=M("login")->userid;
		$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
		if(empty($shop)){
			$this->goAll("暂无权限",1);
		}
		$this->shopid=$shop["shopid"];
		 
	}
	public function onDefault(){
		$shop=M("mod_freeshop_shop")->selectRow("shopid=".$this->shopid);
		 
		$this->smarty->goAssign(array(
			"shop"=>$shop
			 
		));
		$this->smarty->display("freeshop_shop/index.html");
	}
	
	public function onSave(){
		$data=M("mod_freeshop_shop")->postData();
		M("mod_freeshop_shop")->update($data,"shopid=".$this->shopid);
		$this->goAll("保存成功");
	}
	
}
?>