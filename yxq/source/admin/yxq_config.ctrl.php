<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class yxq_configControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
			$data=MM("yxq","yxq_config")->get();
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("yxq_config/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_yxq_config")->postData();
			if($id){
				M("mod_yxq_config")->update($data,"id='$id'");
			}else{
				M("mod_yxq_config")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		
	}

?>