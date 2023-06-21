<?php
class fishing_free_joinModel extends model{
	public $table="mod_fishing_free_join";
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			$uids=[];
			foreach($res as  $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($res as $k=>$v){
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$res[$k]=$v;
			}
			
		}
		return $res;
		
	}
}