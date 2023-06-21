<?php
class cj1_giftControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		
	}
	
	public function onGet(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$gday=date("Ymd");
		$row=M("mod_cj1_giftlog")->selectRow("userid=".$userid." AND gday='".$gday."' ");
		$user=MM("cj1","cj1_user")->get($userid);
		if($row){
			$this->goAll("你已经领取了",1);
		}else{
			M("mod_cj1_giftlog")->insert(array(
				"userid"=>$userid,
				"gday"=>$gday
			));
			MM("cj1","cj1_user")->addGold(array(
				"userid"=>$userid,
				"gold"=>1,
				"typeid"=>2,
				"content"=>"签到获得了1个兑换币，之前".$user['gold']."个,现在".($user['gold']+1)."个"
			));
			$this->goALl("领取成功");
		}
	}
	
}
?>