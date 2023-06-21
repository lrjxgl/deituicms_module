<?php
class im_group_msgControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onSave(){
		$userid=M("login")->userid;
		$groupid=post("groupid","i");
		$content=post("content","h");
		$dateline=time();
		$group=MM("im","im_group")->selectRow("groupid=".$groupid);
		if(empty($group) || $group["status"]!=1){
			$this->goAll("群聊已关闭",1);
		}
		//判断是否加入
		if($group["need_join"]){
			$gu=M("mod_im_group_user")->selectRow("userid=".$userid." AND groupid=".$groupid);
			if(empty($gu)){
				$this->goAll("你还未加入群聊",10);
			}
		}
		if(GROUP_MSG_SAVE=='mysql'){
			M("mod_im_group_msg")->insert(array(
				"userid"=>$userid,
				"groupid"=>$groupid,
				"content"=>$content,
				"dateline"=>$dateline
			));
			M("mod_im_group_msg_index")->delete("groupid=".$groupid);
			M("mod_im_group_msg_index")->insert(array(
				"userid"=>$userid,
				"groupid"=>$groupid,
				"content"=>$content,
				"dateline"=>$dateline
			));
		}else{
			$redis=ImRedis();
			
			$key="im_group_msg_".$groupid;
			 
			$data=array(
				"userid"=>$userid,
				"groupid"=>$groupid,
				"content"=>$content,
				"dateline"=>$dateline
			);
			$redis->lPush($key,json_encode($data));
			$list=$redis->lRange($key, 0, -1);
			//存储index
			$key2="im_group_msg_index";
			$data=array(
				"userid"=>$userid,
				"groupid"=>$groupid,
				"content"=>$content,
				"dateline"=>$dateline
			);
			$iList=$redis->lRange($key2, 0, -1);
			if(!empty($iList)){
				foreach($iList as $v){
					$d=json_decode($v,true);
					if($d["groupid"]==$groupid){
						$redis->lRem($key2,$v);
					}
				}
			}
			$redis->lPush($key2,json_encode($data));
			$iList=$redis->lRange($key2, 0, -1);
			 
		}
		
		$this->goAll("保存成功");
	}
	
}