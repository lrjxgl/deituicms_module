<?php
class im_msg_indexControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$rscount=true;
		$list=M("mod_im_msg_index")->setTable("mod_im_msg_index",$userid,MSG_TABLE_NUM)->select(array(
			"where"=>"userid=".$userid,
			"order"=>"dateline DESC"
		),$rscount);
		if($list){
			foreach($list as $v){
				$uids[]=$v["touserid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
		 
			foreach($list as $k=>$v){
				$v["user_head"]=images_site($us[$v["touserid"]]["user_head"]);
				$v["nickname"]=$us[$v["touserid"]]["nickname"];
				$v["time"]=date("m-d H:i",$v["dateline"]);
				$v["gid"]=0;
				$list[$k]=$v;
			}
		}
		//获取群消息
		$gids=M("mod_im_group_user")->selectCols(array(
			"where"=>" userid=".$userid,
			"fields"=>" groupid"
		));
		$glist=[];
		if(!empty($gids)){
			if(GROUP_MSG_SAVE=='mysql'){
				$glist=M("mod_im_group_msg_index")->select(array(
					"where"=>" groupid in ("._implode($gids).") "
				));
			}else{
				$glist=[];
				$redis=ImRedis();
				$key2="im_group_msg_index";
				$iList=$redis->lRange($key2, 0, -1);
				if(!empty($iList)){
					foreach($iList as $v){
						$d=json_decode($v,true);
						if(in_array($d["groupid"],$gids)){
							$glist[]=$d;
						}
					}
				}
				
			}
		}
		
		if($glist){
			$gplist=MM("im","im_group")->getListByIds($gids);
			
			foreach($glist as $k=>$v){
				$group=$gplist[$v["groupid"]];
				$v["imgurl"]=images_site($group["imgurl"]);
				$v["title"]=$group["title"];
				$v["time"]=date("m-d H:i",$v["dateline"]);
				//$glist[$k]=$v;
				$v["gid"]=$group["groupid"];
				$list[]=$v;
			}
		}
		//根据时间排序
		if($list){
			foreach($list as $k=>$v){
				$times[$k]=$v["dateline"];
			}
			array_multisort ( $times ,  SORT_DESC ,  $list );
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount
		));
		$this->smarty->display("im_msg_index/index.html");
	}
	
}
