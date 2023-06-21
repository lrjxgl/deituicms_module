<?php
class exue_qrcodeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_exue_shop")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,title,imgurl,address"
		));
		 
		$url=HTTP_HOST."/module.php?m=exue_shop&shopid=".SHOPID;
		$wxdata=array(
			"page"=>"pageexue/exue_shop/index",
			"scene"=>"shopid=".SHOPID,
			"width"=>320
		);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"url"=>urlencode($url),
			"wxdata"=>base64_encode(json_encode($wxdata))
		));
		$this->smarty->display("exue_qrcode/index.html");
	}
	
	 
}