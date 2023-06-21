<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class checkin_configControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$data=MM("checkin","checkin_config")->get();
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("checkin_config/index.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=MM("checkin","checkin_config")->postData();
			 
			MM("checkin","checkin_config")->update($data," 1 ");
			$this->goall("保存成功");
		}
		
		
	}

?>