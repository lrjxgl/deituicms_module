<?php
class im_configControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$data=M("mod_im_config")->selectRow();
		$this->smarty->goAssign(array(
			"data"=>$data
		)); 
		$this->smarty->display("im_config/index.html");
	}
	public function onSave(){
		$row=M("mod_im_config")->selectRow();
		$data=M("mod_im_config")->postData();
		if($row){
			M("mod_im_config")->update($data,"1");
		}else{
			M("mod_im_config")->insert($data);
		}
		$this->goAll("保存成功");
	}
}

?>