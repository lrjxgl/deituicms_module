<?php
class mmjz_shop_earnestControl extends skymvc{
	 
	public function onDefault(){
		$data=MM("mmjz","mmjz_shop_earnest")->get(SHOPID);
		
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
		$list=MM("mmjz","mod_mmjz_shop_earnest_log")->select(array(
			"where"=>" shopid=".SHOPID,
			"order"=>" id DESC",
			"limit"=>12
		));
		$this->smarty->goAssign(array(
			"data"=>$data,
			"moneyList"=>$moneyList,
			"list"=>$list
		));
		$this->smarty->display("mmjz_shop_earnest/index.html");
	}
	
	public function onSave(){
		$money=get("money","i");
		$shopmoney=MM("mmjz","mmjz_shop_money")->get(SHOPID);
		//直接扣除
		if($shopmoney["balance"]>$money){
			MM("mmjz","mmjz_shop_money")->addMoney(array(
				"shopid"=>SHOPID,
				"balance"=>-$money,
				"content"=>"支付商家保证金"
			));
			MM("mmjz","mmjz_shop_earnest")->addMoney(array(
				"shopid"=>SHOPID,
				"money"=>$money,
				"content"=>"支付商家保证金"
			));
			$this->goAll("保证金支付成功");
		}else{
			$this->goAll("余额不足",1);
		}
	}
	
	public function onOut(){
		$data=MM("mmjz","mmjz_shop_earnest")->get(SHOPID);
		$bankList=M("mod_mmjz_bankcard")->select(array(
			"where"=>"shopid=".SHOPID
		));	
		$this->smarty->goAssign(array(
			"data"=>$data,
			"bankList"=>$bankList
		));
		$this->smarty->display("mmjz_shop_earnest/out.html");
	}
	public function onOutSave(){
		$money=post("money","i");
		$data=MM("mmjz","mmjz_shop_earnest")->get(SHOPID);
		if($data["money"]<=$money){
			$this->goAll("保证金不足",1);
		}
		if($money<100){
			$this->goAll("资金小于100元不能提取",1);
		}
		$pp=MM("mmjz","mmjz_shop_paypwd")->get(SHOPID);
		if(!$pp){
			$this->goAll("请先设置支付密码",1,0,"/moduleshop.php?m=mmjz_shop_paypwd");
		}
		$paypwd=get_post("paypwd","h");
		if(umd5($paypwd)!=$pp["paypwd"]){
			$this->goAll("支付密码出错",1);
		}
		M("tixian")->begin();		
		$data=M("tixian")->postData();
		$bankid=get_post("bankid","i");
		if(empty($bankid)){
			$this->goAll("请选择提现账户");
		}
		$bank=M("mod_mmjz_bankcard")->selectRow("id=".$bankid);
		$arr=array("yhk_name","yhk_haoma","yhk_huming","telephone","yhk_address","paytype");
		foreach($arr as $k){
			$data[$k]=$bank[$k];
		}
		 
		$data['dateline']=time();
		$data['k']="mod_mmjz_shop";
		$data['objectid']=SHOPID;
		$data["info"]="提取商家保证金".$money."元";
		//减少余额
		MM("mmjz","mmjz_shop_earnest")->addMoney(array(
			"shopid"=>SHOPID,
			"money"=>-$money,
			"content"=>"提取商家保证金".$money."元"
		)); 	 
		M("tixian")->insert($data);
		M("tixian")->commit();
		
		$this->goAll("保证金提取成功");
	}
	
}
