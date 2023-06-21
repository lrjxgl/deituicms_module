<?php
class jdo2o_place_vr3dControl extends skymvc{
	
	public function onDefault(){
		$placeid=get("placeid","i");
		$place=M("mod_jdo2o_place")->selectRow("placeid=".$placeid);
		if($place["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		$data=M("mod_jdo2o_place_vr3d")->selectRow("placeid=".$placeid);
		
		$this->smarty->goAssign(array(
			"place"=>$place,
			"data"=>$data
		));
		$this->smarty->display("jdo2o_place_vr3d/index.html");
	}
	
	public function onSave(){
		$placeid=get_post("placeid","i");
		$place=M("mod_jdo2o_place")->selectRow("placeid=".$placeid);
		if($place["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		$data=M("mod_jdo2o_place_vr3d")->selectRow("placeid=".$placeid);
		$indata=M("mod_jdo2o_place_vr3d")->postData();
		if(empty($data)){
			M("mod_jdo2o_place_vr3d")->insert($indata);
		}else{
			M("mod_jdo2o_place_vr3d")->update($indata,"placeid=".$placeid);
		}
		
		
		$this->goAll("保存成功");
	}
	
	
}