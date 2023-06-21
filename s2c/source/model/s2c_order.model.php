<?php
class s2c_orderModel extends model{
	public $table="mod_s2c_order";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect(){
		
	}
	public function getSendTime(){
		$config=M("mod_s2c_config")->selectRow("1");
		$hour=date("H");
		if($hour>=$config["out_time"]){
			$out=2;
			$sendTime=date("Y-m-d",time()+3600*24*2);
		}else{
			$sendTime=date("Y-m-d",time()+3600*24);
			$out=1;
		}
		return array(
			"sendTime"=>$sendTime,
			"out"=>$out,
			"out_time"=>$config["out_time"]
		);
	}
	public function getStatus($data){
		if($data['status']==0 ){
			if($data['ispay']==0){
				$data['status_name']="待付款";
			}else{
				$data['status_name']="待接单";
			}
			
		}
		if($data['status']==1){
			$data['status_name']="待发货";
		}
		if($data['status']==2 ){
			$data['status_name']="待收货";
		}
	 
		if($data['status']==3 ){
			if($data['israty']==0){
				$data['status_name']="待评价";
			}else{
				$data['status_name']="已完成";
			}			
		}
		if($data['status']>3){
			$data['status_name']="已取消";
		}
		return $data['status_name'];
	}
}