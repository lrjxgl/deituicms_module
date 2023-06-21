<?php
class hongbao_dayControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$token=get("token","h");
		$mtoken=md5(date("Y-m-d"));
		$word="day";
		$html=M("mod_hongbao_html")->selectRow("word='".$word."'");
		
		$this->smarty->assign(array(
			"seo"=>array(
				"title"=>"每天领个小红包，开心度过每一天"
			)
		));
		
		$userid=M("login")->userid;
		$hbuser=MM("hongbao","hongbao_user")->get($userid);
		$day=date("Y-m-d");
		$row=M("mod_hongbao_day")->selectRow("userid=".$userid." AND daytime='".$day."' ");
		$ischeck=0;
		if($row){
			$ischeck=1;
		}
		$list=M("mod_hongbao_day")->select(array(
			"where"=>"daytime='".$day."' ",
			"order"=>"money DESC"
		));
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$list[$k]=$v;
			}
		}
		$canGet=true;
		$isFollow=M("weixin")->checkFollow($userid);
		if(!$isFollow){
			$canGet=false;
		}
		$this->smarty->goAssign(array(
			"hbuser"=>$hbuser,
			"list"=>$list,
			"ischeck"=>$ischeck,
			"data"=>$row,
			"html"=>$html,
			"token"=>$token,
			"canGet"=>$canGet
		));	
		$this->smarty->display("hongbao_day/index.html");
	}
	public function getMoney(){
		$k=mt_rand(1,10000);
		if($k>9990){
			return round($k/1000,2);
		}elseif($k>9900){
			$mk=mt_rand(300,900);
			return $mk/100;
		}elseif($k>9000){
			$mk=mt_rand(100,300);
			return $mk/100;
		}elseif($k>8000){
			$mk=mt_rand(20,100);
			return $mk/100;	 
		}elseif($k>5000){
			$mk=mt_rand(10,20);
			return $mk/100;	 
		}else{
			$mk=mt_rand(1,10);
			return $mk/100;
		}
		 
	}
	public function onGet(){
		 
		$day=date("Y-m-d");
		$userid=M("login")->userid;
		$row=M("mod_hongbao_day")->selectRow("userid=".$userid." AND daytime='".$day."' ");
		if($row){
			$this->goAll("你已经领取咯",1);
		}
		$totalMoney=M("mod_hongbao_day")->selectOne(array(
			"where"=>"  daytime='".$day."' ",
			"fields"=>" sum(money) "
		));
		$money=$this->getMoney();
		if($totalMoney+$money>100){
			$this->goAll("你来晚了，红包已经被领完了",1);
		}
		
		$hbuser=MM("hongbao","hongbao_user")->get($userid);
		M("mod_hongbao_day")->insert(array(
			"userid"=>$userid,
			"daytime"=>$day,
			"money"=>$money
		));
		MM("hongbao","hongbao_user")->addmoney(array(
			"userid"=>$userid,
			"typeid"=>2,
			"money"=>$money,
			"dateline"=>time(),
			"content"=>"你今天签到获得了".$money.",原来".$hbuser['money'].",现在".($hbuser['money']+$money)."。"
		));
		if($hbuser['money']+$money>1 && INWEIXIN){
			MM("hongbao","hongbao_send")->send(array(
				"userid"=>$userid,
				"send_name"=>"福鼎生活网",
				"wishing"=>"祝您每天生活愉快"
			));
			$hbuser["money"]=0;
		}
		 
		$this->goAll("签到成功",0,array(
			"money"=>$money,
			"hbuser"=>$hbuser,
			"ischeck"=>1
		));
	}
	
	public function onTest(){
		echo $this->getMoney();
	}
		
}
?>