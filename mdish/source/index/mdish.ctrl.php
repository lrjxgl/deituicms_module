<?php
class mdishControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-mdish-index");
				$adList=M("ad")->listByNo("uniapp-mdish-ad");
				$navList=M("ad")->listByNo("uniapp-mdish-nav");
				break;
			default:
				$flashList=M("ad")->listByNo("wap-mdish-index");
				$adList=M("ad")->listByNo("wap-mdish-ad");
				$navList=M("ad")->listByNo("wap-mdish-nav");
				break;
		}
		$seo=M("seo")->get("mdish");
		$this->smarty->goAssign(array(
			"seo"=>$seo,
			"navList"=>$navList,
			"flashList"=>$flashList,
			"adList"=>$adList,
		));
		$this->smarty->display("mdish/index.html");
	}
	
	 
}
?>