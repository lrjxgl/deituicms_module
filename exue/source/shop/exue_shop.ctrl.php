<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$data=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".SHOPID));
		 
			$this->smarty->goassign(array(
				"shop"=>$data
			));
			$this->smarty->display("exue_shop/index.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_exue_shop")->postData();
			if($shopid){
				M("mod_exue_shop")->update($data,"shopid='$shopid'");
			}else{
				M("mod_exue_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$status=get_post("status","i");
			M("mod_exue_shop")->update(array("status"=>$status),"shopid=$shopid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_exue_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>