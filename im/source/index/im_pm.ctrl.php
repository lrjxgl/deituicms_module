<?php
class im_pmControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$touserid=get("touserid","i");
		$user=M("user")->getUser($userid);
		$touser=M("user")->getUser($touserid);
		$row=M("friend")->selectRow("userid=".$userid." AND touserid=".$touserid);
		if($row){
			$isFriend=1;
		}else{
			$isFriend=0;
		}
		$limit=10;
		$start=get("per_page","i");
		$rscount=true;
		$list=M("mod_im_msg")->select(array(
			"where"=>"userid=".$userid." AND touserid=".$touserid,
			"limit"=>$limit,
			"start"=>$start,
			"order"=>"id DESC"
		),$rscount);
		if($list){
			foreach($list as $k=>$v){
				$v["isme"]=intval($v["isme"]);
				$list[$k]=$v;
			}
			$list=array_reverse($list);
		}
		$per_page=$start+$limit;
		$per_page=$per_page>=$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"ws_uid"=>imAppToken.$userid,
			"ws_touid"=>imAppToken.$touserid,
			"touser"=>$touser,
			"user"=>$user,
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
			"isFriend"=>$isFriend
		));
		$this->smarty->display("im_pm/index.html");
	}
	
}