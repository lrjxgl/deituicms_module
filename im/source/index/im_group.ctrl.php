<?php
class im_groupControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$gids=[];
		if($userid){
			$gids=M("mod_im_group_user")->selectCols(array(
				"where"=>" userid=".$userid,
				"fields"=>" groupid"
			));
		}
		$list=MM("im","im_group")->select(array(
			"where"=>" status=1",
			"limit"=>24,
			"order"=>"orderindex ASC"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				if(in_array($v["groupid"],$gids) || !$v["need_join"]){
					$v["isjoin"]=1;
				}else{
					$v["isjoin"]=0;
				}
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("im_group/index.html");
	}
	
	public function onHome(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$user=M("user")->getUser($userid,"userid,nickname,user_head");
		$groupid=get("groupid","i");
		$group=MM("im","im_group")->selectRow("groupid=".$groupid);
		if(!$group){
			$list=MM("im","im_group")->groupList();
			$group=$list[$groupid];
		}
		//判断是否加入
		if($group["need_join"]){
			$gu=M("mod_im_group_user")->selectRow("userid=".$userid." AND groupid=".$groupid);
			if(empty($gu)){
				$this->goAll("你还未加入群聊",10);
			}
		}
		$rscount=true;
		$limit=12;
		$start=get("per_page","i");
		if(GROUP_MSG_SAVE=='mysql'){
			$msgList=M("mod_im_group_msg")->select(array(
				"where"=>"groupid=".$groupid,
				"limit"=>$limit,
				"start"=>$start,
				"order"=>"id DESC"
			),$rscount);
		}else{
			$redis=ImRedis();
			$key="im_group_msg_".$groupid;
			$list=$redis->lRange($key, $start, $limit);
			$rscount=$redis->lLen($key);
			$msgList=[];
			if(!empty($list)){
				foreach($list as $v){
					$msgList[]=json_decode($v,true);
				}
			}
		}
		
		if($msgList){
			foreach($msgList as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($msgList as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$msgList[$k]=$v;
				$times[$k]=$v["id"];
			}
			array_multisort ( $times ,  SORT_ASC ,  $msgList );
		}
		$per_page=$start+$limit;
		$per_page=$per_page>=$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"group"=>$group,
			"user"=>$user,
			"list"=>$msgList,
			"ws_gid"=>imAppToken.$groupid,
			"per_page"=>$per_page
		));
		$this->smarty->display("im_group/home.html");
	}
	
}
