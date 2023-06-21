<?php
class aichat_mappControl extends skymvc{
	
	public function onDefault(){
		//prompt
		$prompt="给我讲童话故事";
		//能力 search+tts
		$mappTask=[
			"search"=>true,
			"tts"=>true
		];
		//能力 text+tts
		$mappTask=[
			"text"=>true,
			"tts"=>true
		];
		//能力 图文匹配+tts
		$mappTask2=[
			"text"=>true,
			"img"=>true,
			"tts"=>true
		];
		//search
		$prompt="空中之王";
		$mappTask=[
			"search"=>true,
			"list"=>true,
			"text"=>true,
		];
		$this->smarty->display("aichat_mapp/index.html");
	}
	
	public function onApi(){
		
	}
	
	public function onSearch(){
		$word=get("word","h");
		if(empty($word)){
			$word="今年五一电影推荐";
		}
		
		$list=MM("aichat","aichat_mapp")->search($word);
		print_r($list);
	}
}