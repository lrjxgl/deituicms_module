<?php
class vipcard_optionControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$this->smarty->display("vipcard_option/index.html");
	}
	public function onList(){
		$list=M("mod_vipcard_option")->select(array(
			"where"=>" status=1  "
		));
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onSave(){
		$data=M("mod_vipcard_option")->postData();
		if($data["money"]==0){
			$this->goAll("金额不能为0",1);
		}
		$data["status"]=1;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_vipcard_option")->insert($data);
		$this->goAll("保存成功");
		
	}
	public function onDelete(){
		$id=get("id","i");
		M("mod_vipcard_option")->update(array("status"=>11),"id=".$id);
		$this->goAll("删除成功");
	}
}
?>