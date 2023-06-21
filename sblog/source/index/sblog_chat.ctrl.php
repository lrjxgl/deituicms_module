<?php
class sblog_chatControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onList(){
		$user=M("login")->getUser();
		$list=M("mod_sblog_chat")->select(array(
			"where"=>" status=1 ",
			"limit"=>50,
			"order"=>"id DESC"
		));
		if($list){
			foreach($list as $k=>$v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$list[$k]=$v;
				$times[$k]=$v["id"];
			}
			array_multisort ( $times ,  SORT_ASC ,  $list );
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list,
				"user"=>$user,
				"per_page"=>0
			)
		));
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$data=M("mod_sblog_chat")->postData();
		$data["dateline"]=time();
		$data["userid"]=$userid;
		$data["status"]=1;
		$id=M("mod_sblog_chat")->insert($data);
		$data["id"]=$id;
		
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"data"=>$data
			)
		));
	}
	
}
?>