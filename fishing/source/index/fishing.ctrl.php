<?php
class fishingControl extends skymvc{
	public function onDefault(){
		//广告
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-fishing-index");
				$adList=M("ad")->listByNo("uniapp-fishing-ad");
				$navList=M("ad")->listByNo("uniapp-fishing-nav"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-fishing-index");
				$adList=M("ad")->listByNo("wap-fishing-ad");
				$navList=M("ad")->listByNo("wap-fishing-nav"); 
				break;
		}
		$seo=M("seo")->get("fishing","default"); 
		$this->smarty->goassign(array(
		 
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,
			 
			"seo"=>$seo
		));
		$this->smarty->display("fishing/index.html");
	}
	public function onUser(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid);
		//统计
		$place_num=M("mod_fishing_place")->where("userid=".$userid)->count();
		$blog_num=M("mod_fishing_blog")->where("userid=".$userid)->count();
		$checkin_num=M("mod_fishing_checkin")->where("userid=".$userid)->count();
		$this->smarty->goAssign(array(
			"user"=>$user,
			"place_num"=>$place_num,
			"blog_num"=>$blog_num,
			"checkin_num"=>$checkin_num
		));
		$this->smarty->display("fishing/user.html");
	}
	
	public function onPeople(){
		$fsList=M("user")->Dselect(array(
			"where"=>" 1 ",
			"limit"=>100,
			"fields"=>" userid,user_head,nickname,followed_num",
			"order"=>"followed_num DESC"
		));
		$this->smarty->goAssign(array(
			"fsList"=>$fsList
		));
		$this->smarty->display("fishing/people.html");
	}
	
}