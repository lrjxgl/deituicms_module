<?php
class b2c_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->selectRow(array("where"=>" userid=".$userid));
		$user['user_head']=images_site($user['user_head']);
		$bconfig=M("mod_b2c_config")->selectRow("1");
		//导航
		$navList=M("navbar")->getListByGroup(7);
		$invitecode=M("user_invitecode")->getCode($userid); 
		//统计
		$hisory_num=M("mod_b2c_history")->getcount(" userid=".$userid);
		$fav_num=M("fav")->getcount(" userid=".$userid." AND tablename='mod_b2c_product' ");
		$love_num=M("love")->getcount(" userid=".$userid." AND tablename='mod_b2c_product' ");
		$coupon_num=M("coupon_user")->getcount(" userid=".$userid." AND status=0 ");
		$this->smarty->goAssign(array(
			"data"=>$user,
			"user"=>$user,
			"navList"=>$navList,
			"invitecode"=>$invitecode,
			"bconfig"=>$bconfig,
			"history_num"=>$hisory_num,
			"fav_num"=>$fav_num,
			"love_num"=>$love_num,
			"coupon_num"=>$coupon_num
			 
		));
		$tpl=M("pagetpl")->get("b2c_user","index");
		$this->smarty->display($tpl);
	}
	
	
}