<?php
class household_configControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_household_config")->selectRow("1");
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("household_config/index.html");
	}
	
	public function onSave(){
		$data=M("mod_household_config")->postData();
		$row=M("mod_household_config")->selectRow("1");
		if($row){
			M("mod_household_config")->update($data,"1=1");
		}else{
			M("mod_household_config")->insert($data);
		}
		$this->goAll("保存成功");
	}
}