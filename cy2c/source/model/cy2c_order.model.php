<?php
class cy2c_orderModel extends model{
	public $table="mod_cy2c_order";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect(){
		
	}
	public function getStatus($data){
		if($data['status']==0 ){
			if($data['ispay']==0){
				$data['status_name']="待付款";
			}else{
				$data['status_name']="待发货";
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