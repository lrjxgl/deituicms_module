<?php
class zhuli_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		 
		$url="/moduleadmin.php?m=zhuli_order";
		$status=get('status','i');
		if($status){
			$where="  status=$status";
		}else{
			$where=" status in(0,1,2,3,4,8) ";
		}
		$orderlist=M("mod_zhuli_order")->select(array(
			"where"=>$where,
			"order"=>"orderid DESC"
		));
		 
		$status_list=array(
			0=>"未确认",
			1=>"已确认",
			2=>"已发货",
			3=>"已完成",
			8=>"已取消"
		); 
		if($orderlist){
			foreach($orderlist as $v){
				$pids[]=$v['zlid'];
			}
			$ps=MM("zhuli","zhuli")->getListByIds($pids);
		 
			foreach($orderlist as &$v){
				 
				$v['title']=$ps[$v['zlid']]['title'];
				$v['imgurl']=images_site($ps[$v['zlid']]['imgurl']);
			 
				$v['price']=$ps[$v['zlid']]['price'];
				$v['status_name']=$status_list[$v['status']];
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goASsign(array(
			"orderlist"=>$orderlist,
			"statuslist"=>$status_list,
			"pagelist"=>$pagelist
		));
		$this->smarty->display("zhuli_order/index.html");
		
	}
	
	public function onConfirm(){
		$orderid=get_post("orderid","i");
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['status']!=0){
			$this->goAll("数据出错",1);
		}
		M("mod_zhuli_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		
		$this->goAll("订单确认");
	}
	
	public function onSend(){
		$orderid=get_post("orderid","i");
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['status']>1){
			$this->goAll("数据出错",1);
		}
		M("mod_zhuli_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		
		$this->goAll("订单发送");
	}
	public function onFinish(){
		$orderid=get_post("orderid","i");
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['status']>2){
			$this->goAll("数据出错",1);
		}
		M("mod_zhuli_order")->update(array(
			"status"=>3
		),"orderid=".$orderid);
		
		$this->goAll("订单完成");
	}
	public function onCancel(){
		$orderid=get_post("orderid","i");
		$order=M("mod_zhuli_order")->selectRow("orderid=".$orderid);
		if($order['status']!=0){
			$this->goAll("该订单无法取消",1);
		}
		M("mod_zhuli_order")->update(array(
			"status"=>8
		),"orderid=".$orderid);
		
		$this->goAll("订单取消");
	}
}
?>