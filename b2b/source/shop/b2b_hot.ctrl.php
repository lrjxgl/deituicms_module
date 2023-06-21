<?php
class b2b_hotControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("b2b_hot/index.html");
	}
	
	public function onApi(){
		$vipList=M("mod_b2b_hotvip")->select(array(
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