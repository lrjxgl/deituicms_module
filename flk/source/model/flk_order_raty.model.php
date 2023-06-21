<?php
class flk_order_ratyModel extends model{
	public $table="mod_flk_order_raty";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			foreach($res as $rs){
				$uids[]=$rs["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($res as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$res[$k]=$v;
			}
		}
		return $res;
	}
	
}