<?php
class dodo_loveControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		
	}
	public function onDefault(){
		$doid=get("doid","i");
		$list=M("mod_dodo_love")->select(array(
			"where"=>"doid=".$doid,
			"order"=>"id DESC",
			"limit"=>100
		));
		if($list){
			$uids=array();
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onToggle(){
		M("login")->checkLogin();
		$doid=get("doid","i");
		$userid=M("login")->userid;
		$dodo=M("mod_dodo")->selectRow("id=".$doid);
		$row=M("mod_dodo_love")->selectRow("userid=".$userid." AND doid=".$doid);
		if($row){
			M("mod_dodo_love")->delete("id=".$row["id"]);
			M("mod_dodo")->changenum("love_num",-1,"id=".$doid);
			$this->goAll("已取消鼓励",0,array(
				"action"=>"delete",
				"love_num"=>$dodo["love_num"]-1
			));
		}else{
			M("mod_dodo_love")->insert(array(
				"userid"=>$userid,
				"doid"=>$doid
			));
			M("mod_dodo")->changenum("love_num",1,"id=".$doid);
			$this->goAll("感谢您的鼓励",0,array(
				"action"=>"delete",
				"love_num"=>$dodo["love_num"]+1
			));
			
		}
	}
}