<?php
class tutor_configControl extends skymvc{
	public function onDefault(){
		$tconfig=MM("tutor","tutor_config")->get();
		$this->smarty->goAssign(array(
			"tconfig"=>$tconfig
		));
		$this->smarty->display("tutor_config/index.html");
	}
	public function onSave(){
		$tconfig=MM("tutor","tutor_config")->get();
		$data=MM("tutor","tutor_config")->postData();
		if($data["per_money"]<2 || $data["per_money"]>30){
			$this->goAll("平台抽成需介于2~30之间",1);
		}
		MM("tutor","tutor_config")->update($data," 1 ");
		$this->goAll("success");
	}
}
?>