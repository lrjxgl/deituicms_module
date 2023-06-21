<?php
class ershou_configControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_ershou_config")->selectRow("1");
		 
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("ershou_config/index.html");
	}
	
	public function onSave(){
		$data=M("mod_ershou_config")->postData();
		$row=M("mod_ershou_config")->selectRow("1");
		if($row){
			M("mod_ershou_config")->update($data,"1=1");
		}else{
			M("mod_ershou_config")->insert($data);
		}
		$this->goAll("保存成功");
	}
}