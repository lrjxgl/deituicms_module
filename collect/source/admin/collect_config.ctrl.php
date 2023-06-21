<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class collect_configControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
 
		}
		
		public function onDefault(){
			$this->onAdd();
		}
		
		public function onAdd(){
			$data=M("mod_collect_config")->selectRow();	
			if(empty($data)){
				M("mod_collect_config")->insert(array(
					"id"=>1
				));
				$data['id']=1;
			}
			$this->smarty->assign(array(
				"data"=>$data
			));
			$this->smarty->display("collect_config/add.html");
		}
		
		public function onSave(){
			$data=M("mod_collect_config")->postData();
	
			M("mod_collect_config")->update($data," 1 ");
			$this->goall("保存成功");
		}
		
		
	}

?>