<?php
class diypageControl extends skymvc{
	
	public function onDefault(){
		
	}
	
	public function onDesign(){
		$url="/module.php?m=b2c";
		$this->smarty->goAssign(array(
			"diyDir"=>"uicompent/",
			"url"=>$url
		));
		$this->smarty->display("diypage/design.html");
	}
	
	public function onUi(){
		$ui=get("ui","h");
		$row=M("mod_diypage_ui")->selectRow("ui_key='".$ui."' ");
		require "uicompent/common/".$ui.".php";
	}
	
	public function onApi(){
		$list=array(
			0=>[
				"key"=>"ad",
				"title"=>"广告"
			],
			1=>[
				"key"=>"table",
				"title"=>"虚拟表"
			],
		);
		echo json_encode($list);
	}
	
}
?>