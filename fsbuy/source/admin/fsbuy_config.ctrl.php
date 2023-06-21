<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsbuy_configControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$data=M("mod_fsbuy_config")->selectRow("1");
			if(empty($data)){
				M("mod_fsbuy_config")->insert(array(
					"description"=>"说明"
				));
				$data=M("mod_fsbuy_config")->selectRow("1");
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fsbuy_config/index.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_fsbuy_config")->postData();
			if($id){
				M("mod_fsbuy_config")->update($data,"id='$id'");
			}else{
				M("mod_fsbuy_config")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		
	}

?>