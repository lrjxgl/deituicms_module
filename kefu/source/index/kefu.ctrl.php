<?php
	function replace_msg($str){
		$str=preg_replace("/\[:img-(\d+)\]/" ,"<img class=\"msg-r-img js-bigimg\" src=\"/index.php?m=attach&a=getimg&id=$1\" bsrc=\"index.php?m=attach&a=getimg&type=base&id=$1\">",$str);
		return $str;
	}
	class kefuControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		
		public function onDefault(){
			M("login")->checkLogin();
			$list=MM("kefu","kefu")->select(array(
				"where"=>" status=1 ",
				"limit"=>10
			));
			 if($list){
			 	foreach($list as $k=>$v){
			 		$v["user_head"]=images_site($v["user_head"]);
			 		$list[$k]=$v;
			 	}
			 }
			$this->smarty->goAssign(array(
				"list"=>$list
			)); 
			$this->smarty->display("kefu/index.html");
		}
		
	 
	}
?>