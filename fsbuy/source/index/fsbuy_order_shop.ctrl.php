<?php
class fsbuy_order_shopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	
	
	public function onDefault(){
		$userid=M("login")->userid;
		$url="/module.php?m=fsbuy_order_shop";
		$limit=24;
		$start=get("per_page","i");
		$type=get("type","h");
		switch($type){
			case "done":
				$where=" status=3 ";
				break;
			case "begin":
				$where=" status=1 ";
				break;
			case "doing":
				$where=" status=2 ";
				break;
			default:
					$where=" status in(1,2,3) ";
				break;
		}
		$where.=" AND userid=".$userid;
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" fsid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_fsbuy")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$seo=M("seo")->get("fsbuy");
		$this->smarty->goassign(
			array(
				"seo"=>$seo,
				"list"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"per_page"=>$per_page,
			)
		);
		$this->smarty->display("fsbuy_order_shop/fsbuy.html");
	}
	public function onOrder(){
		$fsid=get("fsid","i");
		$userid=M("login")->userid;
		$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
		if($fsbuy["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$this->smarty->goAssign(array(
			"fsbuy"=>$fsbuy
		));
		$this->smarty->display("fsbuy_order_shop/index.html");
	}	
	public function onlistApi(){
		$fsid=get("fsid","i");
		$userid=M("login")->userid;
		$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
		if($fsbuy["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		 
		$where=" fsid=".$fsid;
		 
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
		$data=MM("fsbuy","fsbuy_order")->select($option);
		$statuslist=MM("fsbuy","fsbuy_order")->statuslist();
		if($data){
			foreach($data as $v){
				$ids[]=$v['fsid'];
				 
			}
			$fsbuys=MM("fsbuy","fsbuy")->getListByIds($ids);
			 
			foreach($data as $k=>$v){
				 
				$v['fsbuy']=$fsbuys[$v['fsid']];
			 	$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=MM("fsbuy","fsbuy_order")->getStatus($v);
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
	}
	public function onCheckOrder(){
		$fsid=get_post("fsid","i");
		$fsbuy=M("mod_fsbuy")->selectRow("fsid=".$fsid);
		if($fsbuy["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$this->smarty->goAssign(array(
			"fsbuy"=>$fsbuy
		));
		$this->smarty->display("fsbuy_order_shop/checkorder.html");
	}
	public function onGetByCode(){
		
	}
	public function onCheckOrderSave(){
		$fsid=get_post("fsid","i");
		$yzm=post("yzm","h");
		$ordercode="";
		$order=M("mod_fsbuy_order")->selectRow("ordercode='".$ordercode."'");
		if(!$order){
			$this->goAll("验证码错误",1);
		}
		if($order["fsid"]!=$fsid){
			$this->goAll("订单信息不符",1);
		}
		M("mod_fsbuy_order")->update(array(
			"status"=>3
		),"orderid=".$order["orderid"]);
		//处理邀请用户赏金
		MM("fsbuy","fsbuy_order")->doInvite($order);
		$this->goAll("订单完成");
	}
}
?>