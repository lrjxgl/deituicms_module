<?php
class forum_configControl extends skymvc{
	public function onDefault(){
		$fconfig=MM("forum","forum_config")->get();
		 
		$this->smarty->goAssign(array(
			"fconfig"=>$fconfig
		));
		$this->smarty->display("forum_config/index.html");
	}
	
	public function onSave(){
		$data=[];
		foreach($_POST as $k=>$v){
			$data[str_format($k,"h")]=post($k,"h");
		}
		$content=arr2str($data);
		$fconfig=MM("forum","forum_config")->get();
		MM("forum","forum_config")->update(array(
			"content"=>$content
		)," 1 ");
		$this->goAll("保存成功");
	}
}