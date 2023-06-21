<?php
class tutor_order_ratyControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$orderid=post("orderid","i");
		$raty_grade=post("raty_grade","r",2);
		$raty_content=post("raty_content","h");
		$order=M("mod_tutor_order")->selectRow("orderid=".$orderid);
		if($order["userid"]!==$userid){
			$this->goAll("暂无权限",1);
		}
		if($order["israty"]){
			$this->goAll("已经评价了",1);
		}
		M("mod_tutor_order")->update(array("israty"=>1),"orderid=".$orderid);
		M("mod_tutor_order_raty")->insert(array(
			"shopid"=>$order["shopid"],
			"userid"=>$userid,
			"lessonid"=>$order["lessonid"],
			"dateline"=>time(),
			"raty_grade"=>$raty_grade,
			"raty_content"=>$raty_content
		));
		
		$this->goAll("感谢您的评价");
	}
	
}