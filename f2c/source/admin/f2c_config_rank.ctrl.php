<?php
class f2c_config_rankControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
		$this->smarty->display("f2c_config_rank/index.html");
	}
	
	public function onList(){
		$data=M("mod_f2c_config_rank")->select(array(
			"where"=>"1",
			"order"=>"min_grade ASC"
		));
		$this->smarty->goAssign(array(
			"list"=>$data
		));
	}
	public function onSave(){
		$id=get_post("id","i");
		$data=M("mod_f2c_config_rank")->postData();
		$row=M("mod_f2c_config_rank")->selectRow("id=".$id);
		if($row){
			M("mod_f2c_config_rank")->update($data,"id=".$id);
		}else{
			M("mod_f2c_config_rank")->insert($data);
		}
		$this->goAll("保存成功");
	}
	public function onDelete(){
		$id=get_post("id","i");
		M("mod_f2c_config_rank")->delete("id=".$id);
		$this->goAll("删除成功");
	}
}