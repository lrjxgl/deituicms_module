<?php
class forum_paihangControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$fsList=M("user")->Dselect(array(
			"where"=>" status=1 ",
			"limit"=>100,
			"fields"=>" userid,user_head,nickname,followed_num",
			"order"=>"followed_num DESC"
		));
		$fconfig=MM("forum","forum_config")->get();
		$time=time()-3600*24*$fconfig["phb_post_time"];
		$wzList=MM("forum","forum")->Dselect(array(
			"where"=>" status=1 AND  dateline>".$time." ",
			"limit"=>24,
			"order"=>"view_num DESC"
		));
		 
		
		$this->smarty->goAssign(array(
			"fsList"=>$fsList,
			"wzList"=>$wzList
		));
		$this->smarty->display("forum_paihang/index.html");
	}
	
	public function onApi(){
		
	}
	
}
?>