<?php
class csc_backControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$list=M("mod_csc_back")->select(array(
			"where"=>$where
		));
		$month=date("Ym",strtotime("-1 month"));
		$isBack=1;
		$row=M("mod_csc_back")->selectRow("userid=".$userid." AND dmonth=".$month);
		if($row){
			$isBack=0;
		}
		$bMoney=0;
		 
		$fanli=0;
		$ym=date("Y-m");
		$tt=M("mod_csc_order")->selectRow(array(
			"where"=>" status=3 AND userid=".$userid." AND createtime like '".$ym."%' ",
			"fields"=>"sum(money) as smoney,sum(express_money) as emoney"
		));
		if($tt){
			$ttmoney=$tt["smoney"]-$tt["emoney"];
			$fanli=$ttmoney/10000;
			if($fanli<0.01){
				$fanli=0.01;
			}elseif($fanli>0.12){
				$fanli=0.12;
			}
			$bMoney=intval(100*$ttmoney*$fanli)/100;
		}
		$data=array(
			"description"=>"福鼎口福生鲜为了回馈忠实顾客，我们举行月底返利活动，消费越多，返越多，越省钱。"
		);
		$this->smarty->goAssign(array(
			"isBack"=>$isBack,
			"list"=>$list,
			"bMoney"=>$bMoney,
			"data"=>$data
		));
		$this->smarty->display("csc_back/index.html");
	}
	
	public function onGetMoney(){
		$userid=M("login")->userid;
		$month=date("Ym",strtotime("-1 month"));
		$ym=date("Y-m",strtotime("-1 month"));
		$row=M("mod_csc_back")->selectRow("userid=".$userid." AND dmonth=".$month);
		if(!$row){
			$money=0;
			$fanli=0;
			$tt=M("mod_csc_order")->selectRow(array(
				"where"=>" status=3 AND  userid=".$userid." AND createtime like '".$ym."%' ",
				"fields"=>"sum(money) as smoney,sum(express_money) as emoney"
			));
			if($tt){
				$ttmoney=$tt["smoney"]-$tt["emoney"];
				$fanli=$ttmoney/10000;
				if($fanli<0.01){
					$fanli=0.01;
				}elseif($fanli>0.12){
					$fanli=0.12;
				}
				$money=$ttmoney*$fanli;
			}
			M("mod_csc_back")->insert(array(
				"userid"=>$userid,
				"dmonth"=>$month,
				"money"=>$money,
				"fanli"=>$fanli,
				"dateline"=>time()
			));
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>$money,
				"content"=>"生鲜月底返利".$money."元"
			));
			$this->goAll("领取成功");
		}else{
			$this->goAll("已经领取过了");
		}
		
	}
	
}