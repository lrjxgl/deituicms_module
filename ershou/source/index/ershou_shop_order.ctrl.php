<?php
class ershou_shop_orderControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onMy(){
		$userid=M("login")->userid;
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$where=" shopid=".$shop["shopid"];
		 
		$type=get("type","h");
		switch($type){
			case "unpay":
				$where.=" AND status=0 AND ispay=0 ";
				break;
			case "unraty":
				$where.=" AND status=3 AND israty=0";
				break;
			 
			case "unreceive":
				$where.=" AND status in(0,1,2) AND ispay=1 ";
				break;
			default:
				$where.=" AND status in(0,1,2,3,4) ";
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
			"statuslist"=>$statuslist
			
		));
		
		$this->smarty->display("ershou_shop_order/my.html");
	}
	public function onShow(){
		$orderid=get_post("orderid","i");
		$order=MM("ershou","ershou_order")->selectRow("orderid=".$orderid);
		$discount_money=0;
		$userid=M("login")->userid;
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$product=M("mod_ershou_product")->selectRow(array(
			"where"=>" productid=".$order["productid"],
			"fields"=>"productid,imgurl,description,price,sold_num"
		));
		 
		$product["imgurl"]=images_site($product["imgurl"]);
		 
		$order["status_name"]=MM("ershou","ershou_order")->getStatus($order);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"product"=>$product,
			"ordercode"=>$ordercode,
			"orderCodeEwm"=>$orderCodeEwm,
			"discount_money"=>$discount_money
		));
		$this->smarty->display("ershou_shop_order/show.html");
	}
	
	
	public function onConfirm(){
		$userid=M("login")->userid;
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$orderid=get("orderid","i");
		$order=M("mod_ershou_order")->selectRow("orderid=".$orderid);
		if($order["shopid"]!=$shop["shopid"]){
			$this->goAll("暂无权限",1);
		}
		M("mod_ershou_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		$this->goAll("操作成功");
	}
	public function onSend(){
		$userid=M("login")->userid;
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$orderid=get_post("orderid","i");
		$order=M("mod_ershou_order")->selectRow("orderid=".$orderid);
		if($order["shopid"]!=$shop["shopid"]){
			$this->goAll("暂无权限",1);
		}
		if($order["status"]>1){
			$this->goAll("已经处理过了",1);
		}
		$express=post("express","h");
		M("mod_ershou_order")->update(array(
			"status"=>2,
			"express"=>$express
		),"orderid=".$orderid);
		$this->goAll("发货成功");
	}
}