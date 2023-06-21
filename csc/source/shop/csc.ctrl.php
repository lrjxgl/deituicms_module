<?php
class cscControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$shop=M("mod_csc_shop")->selectRow("shopid=".SHOPID);
		$shop["imgurl"]=images_site($shop["imgurl"]);
		$admin=M("mod_csc_admin")->selectRow("adminid=".$_SESSION["mcsc_shop_admin"]["adminid"]);
		$timeago=timeago(strtotime($admin["xlasttime"]));
		$neworder=M("mod_csc_order")->selectOne(array(
			"where"=>" ispay=1 AND status=0 AND shopid=".SHOPID,
			"fields"=>" count(*) as ct "
		));
		$dayorder=M("mod_csc_order")->selectRow(array(
			"where"=>" shopid=".SHOPID." AND ispay=1 AND status in(0,1,2,3) AND createtime like '".date("Y-m-d")."%' ",
			"fields"=>" count(orderid) as ordernum,sum(money) as money "
		));
		$usernum=M("mod_csc_order")->selectCols(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"userid",
			"limit"=>1000000
		));
		//橱窗商品
		$prolist=MM("csc","csc_product")->Dselect(array(
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
		$shopsite=0;
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
		$tpl=get("tpl","h");
		if($tpl){
			$this->smarty->display("index/index_main.html");
		}else{
			$this->smarty->display("index/index.html");
		}
		
	}
	
	public function onCenter(){
		$shopsite=0;
		if(M("module")->isInstall("shopsite")){
			
			$row=M("mod_shopsite")->selectRow("shopid=".SHOPID);
			if($row){
				$shopsite=1;
			}
		}
		$shopsite=0;
		$this->smarty->goAssign(array(
			"shopsite"=>$shopsite
		));
		$this->smarty->display("index/center.html");
	}
	
	public function onNotice(){
		$neworder=M("mod_csc_order")->selectRow("ispay=1 AND status=0 ");
		$ctime=date("Y-m-d H:i:s",time()-36000);
		$newMsg=M("mod_csc_guest")->selectRow("status=1 AND ukey='shop' AND createtime>'".$ctime."' ");
		$data=array(
			"neworder"=>$neworder?1:0,
			"newMsg"=>$newMsg?1:0
		);
		echo json_encode($data);
	}
}
?>