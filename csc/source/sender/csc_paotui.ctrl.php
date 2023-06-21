<?php
class csc_paotuiControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$status_list=MM("csc","csc_paotui")->status_list();
		 
		 
		$where=" shopid=".SHOPID;
		$where.=" AND  status=0 AND ispay=1 ";
		$list=M("mod_csc_paotui")->select(array(
			"where"=>$where,
			"order"=>"createtime DESC"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["createtime"]=date("m-d H:i",$v["createtime"]);
				if($v["status"]==0 && $v["ispay"]==0){
					$v["status_name"]="待支付";
				}else{
					$v['status_name']=$status_list[$v['status']];
				}
				
				$v['ispay_name']=$v['ispay']==2?"已支付":"未支付";
				 
				 
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		 
		$this->smarty->display("csc_paotui/index.html");
	}
	
	public function onConfirm(){
		$id=get("id","i");
		$order=MM("csc","csc_paotui")->selectRow("id=".$id);
		if($order["status"]!=0){
			$this->goAll("订单已处理",1);
		}
		if($order["ispay"]==0){
			$this->goAll("订单还未支付",1);
		}
		MM("csc","csc_paotui")->update(array(
			"status"=>1,
			"senderid"=>SENDERID
		),"id=".$id);
		MM("csc","csc_sender_order")->insert(array(
			"shopid"=>SHOPID,
			"senderid"=>SENDERID,
			
			"tablename"=>"paotui",
			"orderid"=>$order["id"],
			"stime"=>time(),
			"status"=>1
		));
		$this->goAll("抢单成功");
	}
	
	public function onFinish(){
		$id=get("id","i");
		$order=MM("csc","csc_paotui")->selectRow("id=".$id);
		if($order["status"]!=2){
			$this->goAll("跑腿办理中",1);
		}
		MM("csc","csc_paotui")->update(array(
			"status"=>3
		),"id=".$id);
		
		$this->goAll("跑腿完成");
	}
	
}	