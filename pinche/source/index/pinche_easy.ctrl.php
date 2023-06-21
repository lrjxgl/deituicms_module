<?php
class pinche_easyControl extends skymvc{
	
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$addrid=get("addrid","i");
		$addr=M("mod_pinche_line_addr")->selectRow("addrid=".$addrid);
		if(empty($addr)){
			$this->goAll("位置不存在",1,0,"/module.php?m=pinche");
		}
		$lineid=$addr["lineid"];
		$line=M("mod_pinche_line")->selectRow("lineid=".$lineid);
		$baiTime=MM("pinche","pinche_line")->getBaiTime();
		$line["basemoney"]=$baiTime?$line["bai_money"]:$line["hei_money"];
		$ppList=M("mod_pinche_people")->select(array(
			"where"=>" status=1 AND userid=".$userid
		)); 
		$this->smarty->goAssign(array(
			"line"=>$line,
			"addr"=>$addr,
			"ppList"=>$ppList,
			"baiTime"=>$baiTime
		));
		$this->smarty->display("pinche_easy/index.html");
	}
}
?>