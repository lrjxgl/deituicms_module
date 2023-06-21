<?php
class vipcard_rechargeControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$userid=get("userid","i");
		$user=M("user")->getUser($userid);
		$this->smarty->goAssign(array(
			"user"=>$user
		));
		$this->smarty->display("vipcard_recharge/index.html");
	}
		
	public function onSave(){
		$nickname=post("nickname","h");
		$user=M("user")->selectRow("nickname='".$nickname."'");
		if(!$user){
			$this->goAll("用户不存在",1);
		}
		$userid=$user["userid"];
		$card=M("mod_vipcard")->selectRow("userid=".$userid);
		if(empty($card)){
			$this->goAll("该用户还未办卡",1);
		}
		$money=post("money","r");
		$paymoney=post("paymoney","r");
		$content="网站充值了现金{$paymoney}元，会员卡{$money}元。";
		M("mod_vipcard_recharge")->begin();
			M("mod_vipcard_recharge")->insert(array(
				"userid"=>$userid,
				"cardid"=>$card["id"],
				"money"=>$money,
				"paymoney"=>$paymoney,
				"createtime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			MM("vipcard","vipcard")->addMoney(array(
				"userid"=>$userid,
				"money"=>$money,
				"content"=>$content
			),$card);
		M("mod_vipcard_recharge")->commit();
		$this->goAll("充值成功");
	}
}