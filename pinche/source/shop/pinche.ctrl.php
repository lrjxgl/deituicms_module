<?php
class pincheControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
	}

	public function onDefault(){
		 
		$this->smarty->display("pinche/index.html");
	}
	
	public function onDache(){
		
		$this->smarty->display("pinche/dache.html");
	} 
	
	public function onUser(){
		$driver=M("mod_pinche_driver")->selectRow("driverid=".DRIVERID);
		$driver["userhead"]=images_site($driver["userhead"]);
		$account=MM("pinche","pinche_driver_account")->get($driver["driverid"]);
		$this->smarty->goAssign(array(
			"driver"=>$driver,
			"account"=>$account
		));
		$this->smarty->display("pinche/user.html");
	}
	
}