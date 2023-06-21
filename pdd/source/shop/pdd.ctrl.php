<?php
class pddControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$shop=M("mod_pdd_shop")->selectRow("shopid=".SHOPID);
		$shop["imgurl"]=images_site($shop["imgurl"]);
		$admin=M("mod_pdd_admin")->selectRow("adminid=".$_SESSION["mpdd_shop_admin"]["adminid"]);
		$timeago=timeago(strtotime($admin["xlasttime"]));
		$neworder=M("mod_pdd_order")->selectOne(array(
			"where"=>" ispay=1 AND status=0 AND shopid=".SHOPID,
			"fields"=>" count(*) as ct "
		));
		$dayorder=M("mod_pdd_order")->selectRow(array(
			"where"=>" shopid=".SHOPID." AND ispay=1 AND status in(0,1,2,3) AND createtime like '".date("Y-m-d")."%' ",
			"fields"=>" count(orderid) as ordernum,sum(money) as money "
		));
		$usernum=M("mod_pdd_order")->selectCols(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"userid",
			"limit"=>1000000
		));
		//橱窗商品
		$prolist=MM("pdd","pdd_product")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND iswindow=1 AND status=1"
		));
		//独立建站
		$shopsite=0;
		if(M("module")->isInstall("shopsite")){
			
			$row=M("mod_shopsite")->selectRow("shopid=".SHOPID);
			if($row){
				$shopsite=1;
			}
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"admin"=>$admin,
			"timeago"=>$timeago,
			"neworder"=>$neworder,
			"dayorder"=>$dayorder,
			"usernum"=>count($usernum),
			"prolist"=>$prolist,
			"shopsite"=>$shopsite
		));
		$this->smarty->display("index/index.html");
	}
	
	public function onCenter(){
		$shopsite=0;
		if(M("module")->isInstall("shopsite")){
			
			$row=M("mod_shopsite")->selectRow("shopid=".SHOPID);
			if($row){
				$shopsite=1;
			}
		}
		$this->smarty->goAssign(array(
			"shopsite"=>$shopsite
		));
		$this->smarty->display("index/center.html");
	}
}
?>