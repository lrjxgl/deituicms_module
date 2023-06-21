<?php
class sblogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-sblog-index");
				$adList=M("ad")->listByNo("uniapp-sblog-ad");
				$navList=M("ad")->listByNo("uniapp-sblog-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-sblog-index");
				$adList=M("ad")->listByNo("wap-sblog-ad");
				$navList=M("ad")->listByNo("wap-sblog-nav");
				break;
		}
		$seo=M("seo")->get("sblog","default");
		$topicList=M("mod_sblog_topic")->select(array(
			"where"=>" isindex=1 AND status=1 "
		));
		$this->smarty->goAssign(array(
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList,
			"seo"=>$seo,
			"topicList"=>$topicList
		));	
		$this->smarty->display("index.html");
	}
	
}
?>