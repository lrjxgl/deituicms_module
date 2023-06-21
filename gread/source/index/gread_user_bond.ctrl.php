<?php
class gread_user_bondControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$guser=MM("gread","gread_user")->get($userid);
		$user=M("user")->getUser($userid,"userid,money");
		$this->smarty->goassign(array(
			"guser"=>$guser,
			"user"=>$user
		));
		$this->smarty->display("gread_user_bond/index.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$guser=MM("gread","gread_user")->get($userid);
		$money=post("money","f");
		$user=M("user")->getUser($userid,"userid,money");
		if($money>$user["money"]){
			$this->goAll("账户余额不足,请先充值",1,0,"/index.php?m=recharge");
		}
		M("user")->addMoney(array(
			"userid"=>$userid,
			"money"=>-$money,
			"content"=>"支付图书借阅保证金，付费{$money}元"
		));
		MM("gread","gread_user")->update(array(
			"bond"=>$guser["bond"]+$money
		),"userid=".$userid);
		$this->goAll("充值成功");
	}
	 
	
}