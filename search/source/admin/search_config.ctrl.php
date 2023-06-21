<?php
class search_configControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$data=M("mod_search_config")->selectRow();
		$this->smarty->assign(array(
			"data"=>$data
		));
		$this->smarty->display("search_config/index.html");
	}
	
	public function onSave(){
		$id=get_post('id','i');
		$data=M("mod_search_config")->postData();
		M("mod_search_config")->update($data,"id=".$id);
		$this->goAll("保存成功");
	}
}

?>