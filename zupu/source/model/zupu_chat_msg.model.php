<?php
class zupu_chat_msgModel extends model{
	public $table="mod_zupu_chat_msg";
	public function Dselect($ops=[],&$rscount=false){
		$list=$this->select($ops,$rscount);
		if(!empty($list)){
			$uids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$list[$k]=$v;
			}
		}
		return $list;
	}
}