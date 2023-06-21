<?php
class fxa_tixianControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,money");
		$orderNum=M("mod_fxa_order")->selectOne(array(
			"where"=>"userid=".$userid." AND ispay=1"
		));
		$fxa_user=MM("fxa","fxa_user")->get($userid);
		$this->smarty->goAssign(array(
			"fxa_user"=>$fxa_user,
			"orderNum"=>$orderNum
		));
		$this->smarty->display("fxa_tixian/index.html");
	}
	
	public function onList(){
		$userid=M("login")->userid;
		$list=M("mod_fxa_tixian")->select(array(
			"where"=>" userid=".$userid,
			"order"=>" id DESC"
		));
		if(!empty($list)){
			$statusList=array(
				0=>"待处理",
				1=>"已完成",
				4=>"已取消"
			);
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("fxa_tixian/list.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$fu=MM("fxa","fxa_user")->get($userid);
		$money=post("money","h");
		if($money<1){
			$this->goAll("提现金额必须大于1元",1);
		}
		if($fu["money"]<$money){
			$this->goAll("余额不足，无法提现",1);
		}
		$re_username=post("re_username","h");
		$re_bankcard=post("re_bankcard","h");
		$re_type=post("re_type","h");
		M("mod_fxa_tixian")->begin();
		M("mod_fxa_tixian")->insert(array(
			"userid"=>$userid,
			"money"=>$money,
			"re_username"=>$re_username,
			"re_bankcard"=>$re_bankcard,
			"re_type"=>$re_type,
			"dateline"=>time()
		));
		MM("fxa","fxa_user")->addMoney(array(
			"userid"=>$userid,
			"money"=>-$money
		));
		M("mod_fxa_tixian")->commit();
		$this->goAll("提现申请成功，请等待审核");
	}
}