<?php
class gread_user_cardControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where="shopid=".SHOPID;
		$start=get("per_page","i");
		$limit=12;
		$order="endtime DESC";
		$ops=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order
		);
		$rscount=true;
		$list=M("mod_gread_user_card")->select($ops,$rscount);
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			$statusList=array(
				0=>"未结算",
				3=>"已结算"
			);
			foreach($list as $k=>$v){
				$v["status_name"]=$v["isfinish"]?'已结算':'未结算';
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$list[$k]=$v;
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("gread_user_card/index.html");
	}
	
}
?>