<?php
class ershou_baojiaControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$limit=12;
		$start=get("per_page","i");
		$rscount=true;
		$list=M("mod_ershou_baojia")->select(array(
			"where"=>" userid=".$userid,
			"order"=>"id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if(!empty($list)){
			$pids=[];
			 
			foreach($list as $v){
				$pids[]=$v["productid"];
				 
			}
			$pros=MM("ershou","ershou_product")->getListByIds($pids);
			$statusList=array(
				0=>"待处理",
				1=>"已接受",
				2=>"已拒绝"
			);
			$pros=MM("ershou","ershou_product")->getListByIds($pids);
			foreach($list as $k=>$v){
				$v["product"]=$pros[$v["productid"]];
				 
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"pagelist"=>$pagelist,
			"rscount"=>$rscount
		));
		$this->smarty->display("ershou_baojia/my.html");
	}
	
	public function onShop(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$limit=12;
		$start=get("per_page","i");
		$rscount=true;
		$list=M("mod_ershou_baojia")->select(array(
			"where"=>" shopid=".$shop["shopid"],
			"order"=>"id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if(!empty($list)){
			$pids=[];
			$uids=[];
			foreach($list as $v){
				$pids[]=$v["productid"];
				$uids[]=$v["userid"];
			}
			$pros=MM("ershou","ershou_product")->getListByIds($pids);
			$statusList=array(
				0=>"待处理",
				1=>"已接受",
				2=>"已拒绝"
			);
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["product"]=$pros[$v["productid"]];
				$v["user"]=$us[$v["userid"]];
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,
			"pagelist"=>$pagelist,
			"rscount"=>$rscount
		));
		$this->smarty->display("ershou_baojia/shop.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$productid=post("productid","i");
		$money=post("money","i");
		$product=M("mod_ershou_product")->selectRow("productid=".$productid);
		$row=M("mod_ershou_baojia")->selectRow("userid=".$userid." AND productid=".$productid);
		if(!empty($row) && $row["status"]==0 ){
			$this->goAll("你已报价了",1);
		}
		M("mod_ershou_baojia")->insert(array(
			"userid"=>$userid,
			"productid"=>$productid,
			"money"=>$money,
			"shopid"=>$product["shopid"],
			"createtime"=>date("Y-m-d H:i:s")
		));
		$this->goAll("success");
	}
	
	public function onPass(){
		$id=get("id","i");
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$row=M("mod_ershou_baojia")->selectRow("id=".$id);
		if($row["shopid"]!=$shop["shopid"]){
			$this->goAll("暂无权限",1);
		}
		M("mod_ershou_baojia")->update(array(
			"status"=>1
		),"id=".$id);
		$this->goAll("审核成功");
	}
	
	public function onForbid(){
		$id=get("id","i");
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$row=M("mod_ershou_baojia")->selectRow("id=".$id);
		if($row["shopid"]!=$shop["shopid"]){
			$this->goAll("暂无权限",1);
		}
		M("mod_ershou_baojia")->update(array(
			"status"=>2
		),"id=".$id);
		$this->goAll("审核成功");
	}
}