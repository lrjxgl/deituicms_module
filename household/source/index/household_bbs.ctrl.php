<?php
class household_bbsControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$gid=8;
		$config=M("mod_household_config")->selectRow("1");
		$gid=$config["forum_gid"];
		if($gid==0){
			$this->goAll("还未设置家电论坛板块",1);
		}
		$group=MM("forum","forum_group")->selectRow("gid=".$gid);
		if($group['status']!=1){
			$this->goAll("本版块暂时无法访问",1);
		}
		$catlist=M("mod_forum_category")->select(array(
			"where"=>" gid=".$gid." AND status=1 ",
			"order"=>" orderindex ASC",
		)); 
		$this->smarty->goAssign(array(
			"group"=>$group,
			"catlist"=>$catlist
		));
		$this->smarty->display("household_bbs/list.html");
	}
}
?>