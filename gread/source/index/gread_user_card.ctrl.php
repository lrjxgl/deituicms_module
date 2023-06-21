<?php
class gread_user_cardControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function getShop(){
		$shopid=get('shopid','i');
		if(!$shopid){
			$shopid=intval($_COOKIE["gread_shopid"]);
		}
		if(!$shopid){
			$this->goAll("请选择书店",1,0,"/module.php?m=gread_shop&a=near");
		}
		$shop=MM("gread","gread_shop")->selectRow("shopid=".$shopid);
		if(empty($shop)){
			$this->goAll("请选择书店",1,0,"/module.php?m=gread_shop&a=near");
		}
		return $shop;
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$guser=MM("gread","gread_user")->get($userid);
		$shop=$this->getShop();
		$list=M("mod_gread_user_card")->select(array(
			"where"=>"userid=".$userid,
			"limit"=>12,
			"order"=>"id DESC"
		)); 
		$this->smarty->goAssign(array(
			"guser"=>$guser,
			"shop"=>$shop,
			"list"=>$list
		));
		$this->smarty->display("gread_user_card/index.html");
	}
	
	public function onMy(){
		$this->smarty->display("gread_user_card/my.html");
	}
	
	public function onAdd(){
		$shop=$this->getShop();
	
		$userid=M("login")->userid;
		$money=$shop["usermoney"];
		$money=min(50,max(30,$money));
		$guser=MM("gread","gread_user")->get($userid);
		$this->smarty->goAssign(array(
			"guser"=>$guser,
			"shop"=>$shop,
			"money"=>$money
		));
		$this->smarty->display("gread_user_card/add.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$guser=MM("gread","gread_user")->get($userid);
		$telephone=post('telephone','h');
		$nickname=post('nickname','h');
		$address=post('address','h');
		$shop=$this->getShop();
		$shopid=$shop["shopid"];
		$money=$shop["usermoney"];
		$money=min(50,max(30,$money));
		$user=M("user")->getUser($userid,"userid,money");
		if($money>$user["money"]){
			$this->goAll("账户余额不足,请先充值",1,0,"/index.php?m=recharge");
		}
		M("user")->addMoney(array(
			"userid"=>$userid,
			"money"=>-$money,
			"content"=>"购买图书借阅卡，付费{$money}元"
		));
		//处理会员卡 有效期
		$endtime=max(time(),strtotime($guser['endtime']))+3600*24*30;
		
		$data=array(
			"userid"=>$userid,
			"shopid"=>$shopid,
			"createtime"=>date("Y-m-d H:i:s"),
			"money"=>$money,
			"shop_money"=>$money*0.8,
			"ispay"=>1,
			"endtime"=>$endtime
		);
		$endtime=date("Y-m-d H:i:s",$endtime);
		M("mod_gread_user_card")->insert($data);
		
		M("mod_gread_user")->update(array(
			"endtime"=>$endtime,
			"telephone"=>$telephone,
			"nickname"=>$nickname,
			"address"=>$address,
			"shopid"=>$shopid
		),"userid=".$userid);
		$this->goAll("保存成功");
	}
	
	 
	
}
?>