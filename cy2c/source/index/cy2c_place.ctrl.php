<?php
class cy2c_placeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
	}
	public function onSet(){
		require_once "extends/hashids/Hashids.php";
		$placecode=get("placecode");
		//解密
		$config=MM("cy2c","cy2c_config")->get();
		$hashids = new Hashids\Hashids($config["md5code"], 6);
		$dec=$hashids->decode($placecode);
		$placeid=$dec[0]; 
		$_SESSION["mcy2c_placeid"]=$placeid;
		if(!get("ajax")){
			header("Location: /module.php?m=cy2c");
			exit;
		}else{
			$this->goAll("success");
		}
		
	}
	
}