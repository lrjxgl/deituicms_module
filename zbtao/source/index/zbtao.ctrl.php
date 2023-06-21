<?php
class zbtaoControl extends skymvc{
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-zbtao-index");
				$adList=M("ad")->listByNo("uniapp-zbtao-ad");
				$navList=M("ad")->listByNo("uniapp-zbtao-nav"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-zbtao-index");
				$adList=M("ad")->listByNo("wap-zbtao-ad");
				$navList=M("ad")->listByNo("wap-zbtao-nav"); 
				break;
		}
		$seo=M("seo")->get("zbtao","default"); 
		//主播
		$zbList=MM("zbtao","zbtao_pp")->Dselect(array(
			"where"=>"status=1 AND isrecommend=1 ",
			"limit"=>6
		));
		//直播
		$liveList=MM("zbtao","zbtao_live")->Dselect(array(
			"where"=>"status=1 AND isrecommend=1 ",
			"limit"=>6
		));
		//商品推荐
		$proList=MM("zbtao","zbtao_live_product")->Dselect(array(
			"where"=>"status=1 AND isrecommend=1 ",
			"order"=>"productid DESC",
			"limit"=>6
		));
		$this->smarty->goassign(array(
			 
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,
			 
			"seo"=>$seo,
			"zbList"=>$zbList,
			"liveList"=>$liveList,
			"proList"=>$proList
		));
		$this->smarty->display("zbtao/index.html");
	}
	
	public function onUser(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head,grade,gold");
		$ppApply=M("mod_zbtao_pp_apply")->selectRow("userid=".$userid." AND status<=2 ");
		$pp=MM("zbtao","zbtao_pp")->getByUserid($userid);
		 
		$this->smarty->goAssign(array(
			"user"=>$user,
			"ppApply"=>$ppApply,
			"pp"=>$pp
		));
		$this->smarty->display("zbtao/user.html");
	}
}
?>