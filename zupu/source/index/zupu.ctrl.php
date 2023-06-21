<?php
class zupuControl extends skymvc{
	
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-zupu-index");
				$adList=M("ad")->listByNo("uniapp-zupu-ad");
				$navList=M("ad")->listByNo("uniapp-zupu-nav"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-zupu-index");
				$adList=M("ad")->listByNo("wap-zupu-ad");
				$navList=M("ad")->listByNo("wap-zupu-nav"); 
				break;
		}
		$seo=M("seo")->get("zupu","default"); 
		$groupList=MM("zupu","zupu_group")->Dselect(array(
			"where"=>" status=1 AND isindex=1 ",
			"limit"=>10
		));
		$newsList=MM("zupu","zupu_news")->Dselect(array(
			"where"=>" status=1 AND isindex=1 ",
			"limit"=>10
		));
		$this->smarty->goAssign(array(
			"groupList"=>$groupList,
			"newsList"=>$newsList,
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,		 
			"seo"=>$seo,
		));
		$this->smarty->display("zupu/index.html");
		
	}
	public function onApi(){
		$gid=1;
		$pid=0;
		$list=MM("zupu","zupu_people")->children($gid,$pid,3);
		print_r($list);
	}
	
	public function onSearch(){
		$xing=get("xing","h");
		$beifen=get("beifen","h");
		$where=" xing=? AND beifen like ? ";
		
		$list=MM("zupu","zupu_group")
			->where($where)
			->limit(24)
			->all($xing,"%".$beifen."%");
		 
		$this->smarty->goAssign(array(
			"xing"=>$xing,
			"beifen"=>$beifen,
			"list"=>$list
		));
		$this->smarty->display("zupu/search.html");
	}
	
	
}