<?php
class pinche_dache_orderControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=pinche_dache_order&a=my";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_pinche_dache_order")->select($option,$rscount);
		$statusList=MM("pinche","pinche_dache_order")->statusList();
		if(!empty($data)){
			$uids=[];
			foreach($data as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["status_name"]=$statusList[$v["status"]];
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		$this->smarty->display("pinche_dache_order/my.html");
	}
	
	public function onShow(){
		$orderid=get_post("orderid","i");
		$order=M("mod_pinche_dache_order")->selectRow(array("where"=>"orderid=".$orderid));
		$statusList=MM("pinche","pinche_dache_order")->statusList();
		$order["status_name"]=$statusList[$order["status"]];
		$this->smarty->goassign(array(
			"order"=>$order
		));
		$this->smarty->display("pinche_dache_order/show.html");
	}
	 
	
	public function onSave(){
		
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		$from_addr=post("from_addr","h");
		$to_addr=post("to_addr","h");
		$telephone=post("telephone","h");
		$description=post("description","h");
		$user_num=post("user_num","i");
		checkEmpty($from_addr,"请填写出发地");
		checkEmpty($to_addr,"请填写目的地");
		if(!is_tel($telephone)){
			$this->goAll("请填写电话",1);
		}
		$data=array(
			"from_addr"=>$from_addr,
			"to_addr"=>$to_addr,
			"telephone"=>$telephone,
			"description"=>$description,
			"user_num"=>$user_num
		);
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		M("mod_pinche_dache_order")->insert($data);
		$this->goAll("success");
	}
	
	public function onFinish(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$orderid=get("orderid","i");
		$order=M("mod_pinche_dache_order")->selectRow("orderid=".$orderid);
		if(empty($order) || $order["status"]>3){
			$this->goAll("订单无法完成",1);
		}
		if($order["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_pinche_dache_order")->update(array(
			 
			"status"=>3,
		 
		),"orderid=".$orderid);
		$this->goAll("订单完成");
	}
	
}