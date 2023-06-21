<?php
class jobControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		//广告轮显
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-job-index");
				$adList=M("ad")->listByNo("uniapp-job-ad");
				$navList=M("ad")->listByNo("uniapp-job-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-job-index");
				$adList=M("ad")->listByNo("wap-job-ad");
				$navList=M("ad")->listByNo("wap-job-nav");
				break;
		}
		$seo=M("seo")->get("job","default");
		$jzList=M("mod_job_jianzhi")->select(array(
			"where"=>" isrecommend=1",
			"order"=>" paymoney DESC",
			"limit"=>6
		));
		$qzList=M("mod_job_quanzhi")->select(array(
			"where"=>" isrecommend=1",
			"order"=>" paymoney DESC",
			"limit"=>6
		));
		$this->smarty->goAssign(array(
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"seo"=>$seo,
			"jzList"=>$jzList,
			"qzList"=>$qzList
		));
		$this->smarty->display("index.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("login")->getUser();
		$invitecode=M("user_invitecode")->getCode($userid);
		$this->smarty->goAssign(
			array(
				"user"=>$user,
				"invitecode"=>$invitecode,
			)
		);
		$this->smarty->display("job/my.html");
	}
	
	
}

?>