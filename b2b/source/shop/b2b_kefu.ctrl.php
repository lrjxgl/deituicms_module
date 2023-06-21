<?php
class b2b_kefuControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		 
	}
	public function onDefault(){
		if(!M("module")->isInstall("kefu")){
			exit("客服系统未安装");
		}
		$_SESSION["kefuOpenKey"]=arr2str(array(
			"tablename"=>"b2b",
			"objectid"=>SHOPID,
		));
		cache()->set($_SESSION["kefuOpenKey"],array(
			"tablename"=>"b2b",
			"objectid"=>SHOPID,
		));
		header("Location: /moduleshop.php?m=kefu");
	}
	public function onMsg(){
		
		$kefu=M("mod_kefu")->selectRow("tablename='b2b' AND status in(0,1) AND objectid=".SHOPID);
		if(empty($kefu)){
			$this->goAll("请先添加客服",1);
		}
		$_SESSION["kefuShopid"]=$kefu["kfid"];
		header("Location: /moduleshop.php?m=kefu_spmsg_index");
	} 
	
}