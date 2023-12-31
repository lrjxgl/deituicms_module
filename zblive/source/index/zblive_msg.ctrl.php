<?php
 class zblive_msgControl extends skymvc{
 	public function __construct(){
 		parent::__construct();
 	}
 	
 	public function onDefault(){
 		
 	}
	public function onList(){
		$room_id=get("room_id","i");
		$list=M("mod_zblive_msg")->select(array(
			"where"=>"room_id=".$room_id,
			"order"=>"id DESC",
			"limit"=>48
		));
		$newList=array();
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$list[$k]=$v;
			}
			$num=count($list)-1;
			for($i=$num;$i>=0; $i--){
				$newList[]=$list[$i];
			}
		}	
		$this->smarty->goAssign(array(
			"list"=>$newList
		));
	}
 	public function onSave(){
 		M("login")->checkLogin();
 		$data=M("mod_zblive_msg")->postData();
		if(empty($data["content"])){
			$this->goAll("内容不能为空",1);
		}
 		$data['userid']=M("login")->userid;
 		M("mod_zblive_msg")->insert($data);
 		$this->goAll("发送成功");
 	}
 }
?>