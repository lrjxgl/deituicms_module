<?php
class group_chatControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		M("login")->checkLogin();
		 
		$userid=M("login")->userid;
		$gid=get_post("gid","i");
		$group=MM("group","group")->get($gid);
		if(!$group['isjoin']){
			$this->goAll("您还未加入，不能进入",1);
		}
		$chat_roomid=base64_encode(DOMAIN."group".$gid);
		$chat_userid=base64_encode(DOMAIN."group".$gid.$userid);
		$this->smarty->goAssign(array(
			"group"=>$group,
			"chat_roomid"=>$chat_roomid,
			"chat_userid"=>$chat_userid
		));
		$this->smarty->display("group_chat/index.html");
		
	}
	
	public function onMsg(){
		$gid=get_post('gid','i');
		$userid=M("login")->userid;
		$data=M("mod_group_chat")->select(array(
			"where"=>" gid=".$gid,
			"order"=>"id DESC",
			"limit"=>30
		));
		
		if($data){
			foreach($data as &$v){
				if($v['userid']==$userid){
					$v['isme']=1;
				}else{
					$v['isme']=0;
				}
			}
			$len=count($data)-1;
			for($i=$len;$i>=0;$i--){
				$ndata[]=$data[$i];
			}
		}
		 
		$this->smarty->goassign(array(
			"data"=>$ndata,
		));
		 
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$user=M("login")->getUser();
		$data=M("mod_group_chat")->postData();
		
		$data['userid']=$user['userid'];
		$data['nickname']=$user['nickname'];
		$data['user_head']=$user['user_head'];
		$data['dateline']=time();
		M("mod_group_chat")->insert($data);
		$this->goAll("保存成功",0,array($data));
	}
	
}
?>