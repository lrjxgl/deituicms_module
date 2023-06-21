<?php
class car_hongbaoControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	
	public  function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$hbuser=MM("hongbao","hongbao_user")->get($userid);
		$hbList=M("mod_hongbao_user_moneylog")->select(array(
			"where"=>"userid=".$userid,
			"order"=>"id DESC"
		));
		if(!empty($hbList)){
			foreach($hbList as $k=>$v){
				$v["timeago"]=timeago($v["dateline"]);
				$hbList[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"hbuser"=>$hbuser,
			"hbList"=>$hbList
		));
		$this->smarty->display("car_hongbao/my.html");
	}
	
	
}
?>