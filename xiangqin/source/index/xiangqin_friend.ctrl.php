<?php
class xiangqin_friendControl extends skymvc{
	
	public function onDefault(){
		$userid=M("login")->userid;
		$uids=M("mod_xiangqin_friend")->selectCols(array(
			"where"=>" userid=".$userid,
			"fields"=>"touserid"
		));
		$list=MM("xiangqin","xiangqin_people")->getListByUids($uids,"id,imgurl,income,userid,truename,birthday,self_desc");
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("xiangqin_friend/index.html");
	}
	
	public function onDelete(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$touserid=get_post("touserid","i");
		M("mod_xiangqin_friend")->delete("userid=".$userid." AND touserid=".$touserid);
		M("mod_xiangqin_friend")->delete("userid=".$touserid." AND touserid=".$userid);
		$this->goAll("success");
	}
}
