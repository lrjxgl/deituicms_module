<?php
class group_typeControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_group_type")->select(array(
			"where"=>"status<11"
		));
		
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("group_type/index.html");
	}
	
	public function onSave(){
		$data=M("mod_group_type")->postData();
		$data['status']=1;
		$catid=get_post('catid','i');
		if($id){
			M("mod_group_type")->update($data,"catid=".$catid);
		}else{
			M("mod_group_type")->insert($data);
		}
		$this->goAll("保存成功");
	}
	
	public function onDelete(){
		$catid=get_post('catid','i');
		M("mod_group_type")->update(array("status"=>11),"catid=".$catid);
		$this->goAll("删除成功");
	}
	
}
?>