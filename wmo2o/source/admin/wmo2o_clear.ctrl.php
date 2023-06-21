<?php
class wmo2o_clearControl extends skymvc{
	
	public function onDefault(){
		
		$this->smarty->display("wmo2o_clear/index.html");
	}
	
	public function onCart(){
		$day=get("day","i");
		$time=date("Y-m-d H:i:s",time()-3600*24*$day);
		MM("wmo2o","wmo2o_cart")->delete("createtime<'".$time."'");
		echo json_encode(array(
			"error"=>0,
			"message"=>"删除成功"
		));
	}
	
}
?>