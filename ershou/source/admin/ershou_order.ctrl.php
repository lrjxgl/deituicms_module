<?php
class ershou_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	 
	public function onDefault(){
		 
		$where="  ";
		 
		$type=get("type","h");
		switch($type){
			case "unpay":
				$where="  status=0 AND ispay=0 ";
				$label="未支付";
				break;
			case "unraty":
				$where=" status=3 AND israty=0";
				$label="待评价";
				break;
			case "unreceive":
				$where="   status in(0,1,2) AND ispay=1 ";
				$label="待收货";
				break;
			case "unsend":
				$where="   status=1  ";
				$label="待发货";
				break;
			case "new":
				$where=" status=0 AND ispay=1 ";
				$label="新订单";
				break;
			default:
				$where.="status in(0,1,2,3,4) ";
				$label="全部订单";
				break;
		}
		$option=array(
			"where"=>$where,
			"order"=>"orderid DESC"
		);
		$data=MM("ershou","ershou_order")->select($option);
		$statuslist=MM("ershou","ershou_order")->statuslist();
		if($data){
			foreach($data as $v){
				$ids[]=$v['productid'];
				 
			}
			$ershous=MM("ershou","ershou_product")->getListByIds($ids);
			 
			foreach($data as $k=>$v){
				 
				$v['ershou']=$ershous[$v['productid']];
			 	$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=MM("ershou","ershou_order")->getStatus($v);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"statuslist"=>$statuslist,
			"label"=>$label
			
		));
		
		$this->smarty->display("ershou_order/index.html");
	}
	public function onShow(){
		 
		$orderid=get_post("orderid","i");
		$order=MM("ershou","ershou_order")->selectRow("orderid=".$orderid);
		 
		$order['status_name']=MM("ershou","ershou_order")->getStatus($order);
		$product=MM("ershou","ershou_product")->selectRow("productid=".$order["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]);
		 
		$shop=MM("ershou","ershou_shop")->get($order["shopid"]);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"shop"=>$shop,
			"product"=>$product
			 
		));
		$this->smarty->display("ershou_order/show.html");
	}
	 
	 
}
?>