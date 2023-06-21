<?php
class paotui_configControl extends skymvc{
	
	public function onDefault(){
		$pconfig=MM("paotui","paotui_config")->get();
		$this->smarty->goAssign(array(
			"pconfig"=>$pconfig
		));
		$this->smarty->display("paotui_config/index.html");
	}
	 
	public function onSave(){
		$row=MM("paotui","paotui_config")->get();
		$data=MM("paotui","paotui_config")->postData();
		MM("paotui","paotui_config")->update($data,"id=".$row["id"]);
		$this->goAll("保存成功");
	}
	
}