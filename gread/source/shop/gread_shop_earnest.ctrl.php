<?php
class gread_shop_earnestControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$data=MM("gread","gread_shop_earnest")->get(SHOPID);
		
		$moneyList=array(			 
			1000,		 
			5000,
			10000
		);
		if($data){
			foreach($moneyList as $k=>$v){
				if($v<=$data['money']){
					unset($moneyList[$k]);
				}
			}
		}
		
		$this->smarty->goAssign(array(
			"data"=>$data,
			"moneyList"=>$moneyList
		));
		$this->smarty->display("gread_shop_earnest/index.html");
	}
	
	public function onSave(){
		$money=post("money","i");
		$row=MM("gread","gread_shop_earnest")->get(SHOPID);
		MM("gread","gread_shop_earnest")->update(array(
			"lasttime"=>date("Y-m-d H:i:s"),
			"endtime"=>date("Y-m-d H:i:s",time()+3600*24*365),
			"money"=>$row["money"]+$money
		),"shopid=".SHOPID);
		M("mod_gread_shop_earnest_log")->insert(array(
			"shopid"=>SHOPID,
			"createtime"=>date("Y-m-d H:i:s"),
			"money"=>$money,
			"content"=>"追加保证金".$money."元"
		));
		M("mod_gread_shop")->update(array(
			"earnest"=>$row["money"]+$money
		),"shopid=".SHOPID);
		$this->goAll("success");
		
	}
	
}
