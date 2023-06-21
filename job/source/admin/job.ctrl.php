<?php
class jobControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	public function onDefault(){
		$data=M("mod_job")->selectRow("1");
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("index.html");
	}
	public function onSave(){
		$row=M("mod_job")->selectRow("1");
		$data=M("mod_job")->postData();
		if($row){
			M("mod_job")->update($data,"id=".$row["id"]);
		}else{
			M("mod_job")->insert($data);
		}
		$this->goAll("保存成功");
	}
}

?>