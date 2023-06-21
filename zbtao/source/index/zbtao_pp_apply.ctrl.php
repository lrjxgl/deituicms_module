<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_pp_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$data=M("mod_zbtao_pp_apply")->selectRow("userid=".$userid);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					 
				)
			);
			$this->smarty->display("zbtao_pp_apply/index.html");
		}
		
		public function onAdd(){
			$ppid=get_post("ppid","i");
			if($ppid){
				$data=M("mod_zbtao_pp_apply")->selectRow(array("where"=>"ppid=".$ppid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zbtao_pp_apply/add.html");
		}
		
		public function onSave(){
			$ppid=get_post("ppid","i");
			$data=M("mod_zbtao_pp_apply")->postData();
			if($ppid){
				M("mod_zbtao_pp_apply")->update($data,"ppid='$ppid'");
			}else{
				M("mod_zbtao_pp_apply")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		
	}

?>