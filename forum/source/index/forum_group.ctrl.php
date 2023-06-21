<?php
class forum_groupControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$list=MM("forum","forum_group")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>"orderindex ASC",
			"fields"=>"gid,title,imgurl,view_num,topic_num,comment_num,description"
		));
		$res=M("mod_forum_group_admin")->select(array(
			"fields"=>"userid,gid",
			"where"=>"status=1"
		));
		if($res){
			foreach($res as $rs){
				$uids[]=$rs["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname");
			foreach($res as $rs){
				if(isset($us[$rs["userid"]])){
					$gs[$rs["gid"]][]=$us[$rs["userid"]];
				}
			}
			if($list){
				 
				foreach($list as $k=>$v){
					if(isset($gs[$v["gid"]])){
						$adm=$gs[$v["gid"]];
					}else{
						$adm=[];
					}
					$list[$k]["admin"]=$adm;
				}
			}
		}
	 
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("forum_group/index.html");
	}
	
	public function onCatlist(){
		$gid=get_post("gid","i");
		$catlist=M("mod_forum_category")->select(array(
			"where"=>" gid=".$gid." AND status=1 "
		));
		$this->goAll("success",0,$catlist);
	}
	
}