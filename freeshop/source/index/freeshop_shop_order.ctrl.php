<?php
class freeshop_shop_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function checkShop($userid){
		 
		$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		return $shop;
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$where=" shopid=".$shopid;
		 
		$type=get("type","h");
		switch($type){
			case "new":
				$where.=" AND status=0 AND ispay=1 ";
				break;
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
		$data=MM("freeshop","freeshop_order")->select($option);
		$statuslist=MM("freeshop","freeshop_order")->statuslist();
		if($data){
			foreach($data as $v){
				$ids[]=$v['productid'];
				 
			}
			$freeshops=MM("freeshop","freeshop_product")->getListByIds($ids);
			 
			foreach($data as $k=>$v){
				 
				$v['freeshop']=$freeshops[$v['productid']];
			 	$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=MM("freeshop","freeshop_order")->getStatus($v);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"statuslist"=>$statuslist,
			"type"=>$type
		));
		
		$this->smarty->display("freeshop_shop_order/my.html");
	}
	public function onShow(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$orderid=get_post("orderid","i");
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
		$userid=M("login")->userid;
		if($order["shopid"]!=$shopid){
			$this->goAll("暂无权限",1);
		}
		$order['status_name']=MM("freeshop","freeshop_order")->getStatus($order);
		$product=MM("freeshop","freeshop_product")->selectRow("productid=".$order["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]);
		 
		$shop=MM("freeshop","freeshop_shop")->get($order["shopid"]);
		$this->smarty->goAssign(array(
			"order"=>$order,
			"shop"=>$shop,
			"product"=>$product
			 
		));
		$this->smarty->display("freeshop_shop_order/show.html");
	}
	public function onCheckCode(){
		$this->smarty->display("freeshop_shop_order/checkcode.html");
	}
	public function onCodeOrder(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$code=post("code","h");
		$oc=M("mod_freeshop_order_code")->selectRow("ordercode='".$code."' AND shopid=".$shopid." ");
		if(empty($oc)){
			$this->goAll("验单码不存在",1);
		}
		$orderid=$oc["orderid"];
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
		$order['status_name']=MM("freeshop","freeshop_order")->getStatus($order);
		$product=MM("freeshop","freeshop_product")->selectRow("productid=".$order["productid"]);
		$product["imgurl"]=images_site($product["imgurl"]); 
		$this->goAll("验证成功",0,array(
			"order"=>$order,
			"product"=>$product,
			"ordercode"=>$oc
		)); 
	}
	
	public function onCodeFinish(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$code=post("code","h");
		$oc=M("mod_freeshop_order_code")->selectRow("ordercode='".$code."' AND shopid=".$shopid." ");
		$orderid=$oc["orderid"];
		$order=MM("freeshop","freeshop_order")->selectRow("orderid=".$orderid);
		if($order["status"]>=3){
			$this->goAll("订单已取消",1);
		}
		if($oc && $oc["isuse"]==0){
			M("mod_freeshop_order_code")->begin();
			M("mod_freeshop_order_code")->update(array(
				"isuse"=>1,
				"checktime"=>time()
			),"ordercode='".$code."' ");
			$res=MM("freeshop","freeshop_order")->finish($orderid);
			if($res["error"]){
				M("mod_freeshop_order_code")->rollback();
				$this->goAll($res["message"],1);
				
			}
			M("mod_freeshop_order_code")->commit();
			$order['status_name']=MM("freeshop","freeshop_order")->getStatus($order);
			$this->goAll("验证成功",0,array(
				"order"=>$order
			));
		}else{
			$this->goAll("验证失败",1);
		}
	}
	
	public function onConfirm(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$orderid=get("orderid","i");
		$order=M("mod_freeshop_order")->selectRow("orderid=".$orderid);
		if($order["shopid"]!=$shop["shopid"]){
			$this->goAll("暂无权限",1);
		}
		M("mod_freeshop_order")->update(array(
			"status"=>1
		),"orderid=".$orderid);
		$this->goAll("操作成功");
	}
	public function onSend(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$orderid=get("orderid","i");
		$order=M("mod_freeshop_order")->selectRow("orderid=".$orderid);
		if($order["shopid"]!=$shop["shopid"]){
			$this->goAll("暂无权限",1);
		}
		M("mod_freeshop_order")->update(array(
			"status"=>2
		),"orderid=".$orderid);
		$this->goAll("发货成功");
	}
	 
}
?>