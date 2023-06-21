<?php
class mdish_lottery_joinControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onSave(){
		$userid=M("login")->userid;
		$sday=date("Ymd");
		$join=M("mod_mdish_lottery_join")->selectRow("userid=".$userid." AND sday=".$sday);
		if($join){
			$this->goAll("你已经参与抽奖了",1);
		}
		$address=post('address','h');
		$nickname=post('nickname','h');
		$telephone=post('telephone','h');
		if(empty($address) || empty($nickname) || empty($telephone)){
			$this->goAll("请确认联系方式",1);
		}
		M("user_lastaddr")->add(array(
			"address"=>$address,
			"nickname"=>$nickname,
			"telephone"=>$telephone
			
		),$userid);
		M("mod_mdish_lottery_join")->insert(array(
			"userid"=>$userid,
			"sday"=>$sday,
			"address"=>$address,
			"nickname"=>$nickname,
			"telephone"=>$telephone
		));
		 
		$this->goAll("参与抽奖成功");
	}
	
}