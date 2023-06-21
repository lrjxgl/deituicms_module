<?php
class freeshop_shop_commissionControl extends skymvc{
	public $shopid=0;
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		$userid=M("login")->userid;
		$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
		if(empty($shop)){
			$this->goAll("暂无权限",1);
		}
		$this->shopid=$shop["shopid"];
		 
	}
	public function onDefault(){
		$commission=MM("freeshop","freeshop_shop_commission")->get($this->shopid);
		$this->smarty->goAssign(array(
			"commission"=>$commission
		));
		$this->smarty->display("freeshop_shop_commission/index.html");
	}
	
	public function onSave(){
		$per=post("per","i");
		$commission=MM("freeshop","freeshop_shop_commission")->get($this->shopid);
		if($commission["etime"]>time()){
			$this->goAll("到".date("Y-m-d H:i:s",$commission["etime"])."才能修改",1);
		}
		MM("freeshop","freeshop_shop_commission")->update(array(
			"per"=>$per,
			"stime"=>time(),
			"etime"=>time()+3600*24*7
		),"shopid=".$this->shopid);
		$this->goAll("保存成功");
	}
	
}