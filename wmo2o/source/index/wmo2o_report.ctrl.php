<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class wmo2o_reportControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
		 
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			if($id){
				$data=MM("wmo2o","wmo2o_report")->selectRow(array("where"=>"id={$id}"));
				
			}
			$shopid=get("shopid","i");
			$typelist=MM("wmo2o","wmo2o_report")->typelist();
			$this->smarty->goassign(array(
				"data"=>$data,
				"typelist"=>$typelist,
				"shopid"=>$shopid
			));
			$this->smarty->display("wmo2o_report/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$userid=M("login")->userid;
			$data=MM("wmo2o","wmo2o_report")->postData();
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			MM("wmo2o","wmo2o_report")->insert($data);
			$this->goall("感谢您的举报");
		}
		
		 
		
		
	}

?>