<?php
class flk_qrcodeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_flk_shop")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,shopname,imgurl,address"
		));
		 
		$url=HTTP_HOST."/module.php?m=flk_shop&shopid=".SHOPID;
		$wxdata=array(
			"page"=>"pageflk/flk_shop/index",
			"scene"=>"shopid=".SHOPID,
			"width"=>320
		);
		$payurl=HTTP_HOST."/module.php?m=flk_shop_pay&shopid=".SHOPID;
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"qrcodeH5"=>HTTP_HOST."/index.php?m=qrcode&content=".urlencode($url),
			"qrcodePay"=>HTTP_HOST."/index.php?m=qrcode&content=".urlencode($payurl),
			"qrcodeWxapp"=>HTTP_HOST."/index.php?m=open_wxapp&a=GetWXACode&data=".base64_encode(json_encode($wxdata))
		));
		$this->smarty->display("flk_qrcode/index.html");
	}
	
	 
}