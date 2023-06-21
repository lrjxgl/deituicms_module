<?php
class cy2c_placeorderModel extends model{
	public $table="mod_cy2c_placeorder";
	public function get($placeid){
		$ctime=date("Y-m-d H:i:s",time()-3600*12); 
		$row=$this->selectRow(array(
			"where"=>"placeid=".$placeid." AND isfinish=0 AND createtime>'".$ctime."' ",
			"order"=>"poid DESC"
		));
		if(empty($row)){
			$this->insert(array(
				"placeid"=>$placeid,
				"createtime"=>date("Y-m-d H:i:s"),
				"updatetime"=>date("Y-m-d H:i:s")
				 
			));
			$row=$this->selectRow(array(
				"where"=>"placeid=".$placeid." AND isfinish=0 AND createtime>'".$ctime."' ",
				"order"=>"poid DESC"
			));
		}
		return $row;
	}
}