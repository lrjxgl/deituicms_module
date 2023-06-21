<?php
class f2c_tixianControl extends skymvc{
	public $team;
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);
		if(empty($team)){
			$this->goAll("您还不是团长",1);
		}
		$this->team=$team;
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$bankList=M("bankcard")->select(array(
			"where"=>" status in(0,1,2) AND userid=".$userid
		));
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);
		$this->smarty->goAssign(array(
			"bankList"=>$bankList,
			"team"=>$team
		));
		$this->smarty->display("f2c_tixian/index.html");
	}
	
	public function onSave(){
		$userid=M("login")->userid;
		$bankid=post("bankid","i");
		$money=post("money","r");
		$bank=M("bankcard")->selectRow("id=".$bankid);
		if(empty($bank)){
			$this->goAll("请选择提现账户",1);
		}
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);
		$user=M("user")->selectRow("userid=".$userid);
		if($team["money"]<$money){
			$this->goAll("提现金额不足",1);
		}
		if($money<100){
			$this->goAll("提现金额不能小于100",1);
		}
		M("tixian")->begin();
		MM("f2c","f2c_team")->addMoney(array(
			"teamid"=>$team["teamid"],
			"money"=>-$money,
			"createtime"=>date("Y-m-d H:i:"),
			"content"=>"您申请提现￥".$money,
		));
		M("tixian")->insert(array(
			"k"=>"f2c_team",
			"objectid"=>$team["teamid"],
			"money"=>$money,
			"dateline"=>time(),
			"info"=>"社区团购团长提现",
			"yhk_name"=>$bank["yhk_name"],
			"yhk_haoma"=>$bank["yhk_haoma"],
			"yhk_huming"=>$bank["yhk_huming"],
			"yhk_address"=>$bank["yhk_address"],
			"telephone"=>$user["telephone"]
		));
		
		M("tixian")->commit();
		$this->goAll("提现申请成功");
	}
	
	public function onList(){
		$userid=M("login")->userid;
		$team=MM("f2c","f2c_team")->selectRow("userid=".$userid);
		$user=M("user")->selectRow("userid=".$userid);
		$ops=array(
			"where"=>" k='f2c_team' AND objectid=".$team["teamid"],
			 "order"=>" id DESC"
		);
		$list=M("tixian")->select($ops);
		$statusList=M("tixian")->status_list();
		if($list){
			foreach($list as $k=>$v){
				$v["timeago"]=timeago($v["dateline"]);
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
		));
		$this->smarty->display("f2c_tixian/list.html");
	}
}