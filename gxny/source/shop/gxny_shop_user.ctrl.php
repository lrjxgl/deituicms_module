<?php
class gxny_shop_userControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$type=get("type","h");
		$this->smarty->goAssign(array(
			"type"=>$type
		));
		$this->smarty->display("gxny_shop_user/index.html");
	}
	
	public function onOrder(){
		$start=get("per_page","i");
		$limit=24;
		$sql="
			select userid,sum(money) as money,count(1) as num from ".table('mod_gxny_order')."
			where shopid=".SHOPID." AND status<4
			group by userid	
			limit $start,$limit 
		";
		$data=M("mod_gxny_order")->getAll($sql);
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=$us[$v['userid']]['user_head'];
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data
		));
		
	}
	
	public function onView(){
		$start=get("per_page","i");
		$limit=24;
		$where=" shopid=".SHOPID;
		$data=M("mod_gxny_view_user")->select(array(
			"where"=>$where,
			"order"=>"lasttime DESC",
			"start"=>$start,
			"limit"=>$limit
		));
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=$us[$v['userid']]['user_head'];
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data
		));
	}
	
}