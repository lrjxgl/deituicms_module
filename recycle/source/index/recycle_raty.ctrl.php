<?php
class recycle_ratyControl extends skymvc{
	
	public function onDefault(){
		
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$raty_grade=post("raty_grade","h");
		$raty_content=post("raty_content","h");
		if(empty($raty_grade) || empty($raty_content)){
			$this->goAll("请填写完整评价信息",1);
		}
		$recycleid=post("recycleid","i");
		$row=M("mod_recycle_raty")->selectRow("recycleid=".$recycleid);
		$recycle=M("mod_recycle")->selectRow("id=".$recycleid);
		if($row){
			$this->goAll("你已经评价了",1);
		}
		M("mod_recycle_raty")->insert(array(
			"userid"=>$userid,
			"shopid"=>$recycle["shopid"],
			"recycleid"=>$recycleid,
			"createtime"=>date("Y-m-d H:i:s"),
			"raty_grade"=>$raty_grade,
			"raty_content"=>$raty_content
		));
		M("mod_recycle")->update(array(
			"israty"=>1
		),"id=".$recycleid);
		$this->goAll("评价成功");
	}
	
}