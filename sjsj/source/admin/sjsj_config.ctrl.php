<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sjsj_configControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
			 
			$data=MM("sjsj","sjsj_config")->get();
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("sjsj_config/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_sjsj_config")->postData();
			if($id){
				M("mod_sjsj_config")->update($data,"id=".$id);
			}else{
				M("mod_sjsj_config")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_sjsj_config")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_sjsj_config")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>