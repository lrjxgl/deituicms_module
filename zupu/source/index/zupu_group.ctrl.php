<?php
class zupu_groupControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onShow(){
		$gid=get("gid","i");
		$group=MM("zupu","zupu_group")->selectRow("gid=".$gid);
		$group["imgurl"]=images_site($group["imgurl"]);
		$newsList=MM("zupu","zupu_news")->Dselect(array(
			"where"=>" gid=".$gid." AND status=1 AND isrecommend=1 ",
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"group"=>$group,
			"newsList"=>$newsList
		));
		$this->smarty->display("zupu_group/show.html");
	}
}