<?php
class wmo2o_hotControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("wmo2o_hot/index.html");
	}
	
	public function onApi(){
		$vipList=M("mod_wmo2o_hotvip")->select(array(
			"where"=>" status=1"
		));
		echo json_encode(array(
			"error"=>"0",
			"message"=>"success",
			"data"=>array(
				"vipList"=>$vipList
			)
		));
	}
}
?>