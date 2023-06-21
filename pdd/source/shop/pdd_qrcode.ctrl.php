<?php
class pdd_qrcodeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_pdd_shop")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,shopname,imgurl,address"
		));
		 
		$url=HTTP_HOST."/module.php?m=pdd_shop&shopid=".SHOPID;
		$wxdata=array(
			"page"=>"pagepdd/pdd_shop/index",
			"scene"=>"shopid=".SHOPID,
			"width"=>320
		);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"url"=>urlencode($url),
			"wxdata"=>base64_encode(json_encode($wxdata))
		));
		$this->smarty->display("pdd_qrcode/index.html");
	}
	
	 
}