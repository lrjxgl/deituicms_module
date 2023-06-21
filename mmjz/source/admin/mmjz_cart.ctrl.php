<?php
class mmjz_cartControl extends skymvc{
	public function onDefault(){
		$this->smarty->display("mmjz_cart/index.html");
	}
	public function onClear(){
		$day=get("day","i");
		$time=date("Y-m-d H:i:s",time()-3600*24*$day);
		MM("mmjz","mmjz_cart")->delete("createtime<'".$time."'");
		echo json_encode(array(
			"error"=>0,
			"message"=>"删除成功"
		));
	}
}