<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_configControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$data=M("mod_fishing_config")->selectRow(array("where"=>" 1 "));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_config/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_fishing_config")->postData();
			if($id){
				M("mod_fishing_config")->update($data,"id='$id'");
			}else{
				M("mod_fishing_config")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fishing_config")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fishing_config")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>