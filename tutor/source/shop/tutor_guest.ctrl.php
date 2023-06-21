<?php
class tutor_guestControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
	}
	public function onDefault(){
		if(!M("module")->isInstall("kefu")){
			exit("客服系统为安装");
		}
		$_SESSION["kefuOpenKey"]=arr2str(array(
			"tablename"=>"tutor",
			"objectid"=>SHOPID,
		));
		cache()->set($_SESSION["kefuOpenKey"],array(
			"tablename"=>"tutor",
			"objectid"=>SHOPID,
		));
		header("Location: /moduleshop.php?m=kefu");
	}
	public function onMsg(){
		
		$kefu=M("mod_kefu")->selectRow("tablename='tutor' AND status in(0,1) AND objectid=".SHOPID);
		if(empty($kefu)){
			$this->goAll("请先添加客服",1);
		}
		$_SESSION["kefuShopid"]=$kefu["kfid"];
		header("Location: /moduleshop.php?m=kefu_spmsg_index");
	} 
	
}
?>