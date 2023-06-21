<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class youyao_shop_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
		}
		
		public function onAdd(){
			$userid=M("login")->userid;
			$data=M("mod_youyao_shop_apply")->selectRow(array("where"=>"userid=".$userid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("youyao_shop_apply/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$shopid=get_post("shopid","i");
			$data=M("mod_youyao_shop_apply")->postData();
			
			if($shopid){
				M("mod_youyao_shop_apply")->update($data,"shopid=".$shopid);
			}else{
				$data["userid"]=$userid;
				M("mod_youyao_shop_apply")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		 
		
		
	}

?>