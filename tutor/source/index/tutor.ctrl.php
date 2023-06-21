<?php
class tutorControl extends skymvc{
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-tutor-index");
				$adList=M("ad")->listByNo("uniapp-tutor-ad");
				$navList=M("ad")->listByNo("uniapp-tutor-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-tutor-index");
				$adList=M("ad")->listByNo("wap-tutor-ad");
				$navList=M("ad")->listByNo("wap-tutor-nav");
				break;
		}
		$site=M("site")->get();
		$seo=M("seo")->get("tutor","default"); 
		$this->smarty->assign(array(
			
			"site"=>$site
		));
		//老师推荐
		$shopList=MM("tutor","tutor_shop")->Dselect(array(
			"where"=>" status=1 ",
			"limit"=>4
		)); 
		//课程推荐
		$lessonList=MM("tutor","tutor_lesson")->Dselect(array(
			"where"=>" status=1 ",
			"limit"=>4
		));
		if($lessonList){
			foreach($lessonList as $v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("tutor","tutor_shop")->getListByIds($shopids);
			foreach($lessonList as $k=>$v){
				$v["shop"]=$sps[$v["shopid"]];
				$lessonList[$k]=$v;
			}
		}  
		$this->smarty->goAssign(array(
			"seo"=>$seo,
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"shopList"=>$shopList,
			"lessonList"=>$lessonList
		));	
		$this->smarty->display("tutor/index.html");
	}
}