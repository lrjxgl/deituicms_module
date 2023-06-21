<?php
class xiangqinControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$my=M("mod_xiangqin_join")->selectRow(array("where"=>"userid=".$userid));
		$unJoin=1;
		if(!empty($my)){
			$unJoin=0;
		}
		$seo=M("seo")->get("xiangqin","index");
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-xiangqin-index");
				$adList=M("ad")->listByNo("uniapp-xiangqin-ad");
				$navList=M("ad")->listByNo("uniapp-xiangqin-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-xiangqin-index");
				$adList=M("ad")->listByNo("wap-xiangqin-ad");
				$navList=M("ad")->listByNo("wap-xiangqin-nav");
				break;
		}
		$this->smarty->goAssign(array(
			"unJoin"=>$unJoin,
			"seo"=>$seo,
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
		));
		$this->smarty->display("xiangqin/index.html");
	}
}