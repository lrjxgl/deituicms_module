<?php
class csc_qrcodeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$shop=M("mod_csc_shop")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,shopname,imgurl,address"
		));
		 
		$url=HTTP_HOST."/module.php?m=csc_shop&shopid=".SHOPID;
		$wxdata=array(
			"page"=>"pagecsc/csc_shop/index",
			"scene"=>"shopid=".SHOPID,
			"width"=>320
		);
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"url"=>urlencode($url),
			"wxdata"=>base64_encode(json_encode($wxdata))
		));
		$this->smarty->display("csc_qrcode/index.html");
	}
	
	 
}