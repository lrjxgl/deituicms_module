<?php
class imgdiy_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function priceList($typeid){
		if($typeid==1){
			return 11;
		}else{
			return 13;
		}
	}
	public function onOrder(){
		$userid=M("login")->userid;
		$data['nickname']=post("nickname",'h');
		$data['telephone']=post("telephone",'h');
		$data['address']=post('address','h');
		if(empty($data['nickname'])){
			$this->goAll("请输入联系人",1);
		}
		if(empty($data['telephone'])){
			$this->goAll("请输入电话",1);
		}
		if(empty($data['address'])){
			$this->goAll("请输入收货地址",1);
		}
		$data['amount']=post("amount",'i');
		$data['typeid']=post("typeid",'i');
		$data['price']=$this->priceList($data['typeid']);
		$data['money']=$data['price']*$data['amount'];
		$data['userid']=$userid;
		M("mod_imgdiy_order")->insert($data);
		$this->goAll("下单成功");
	}
	
}