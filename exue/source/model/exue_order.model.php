<?php
class exue_orderModel extends model{
	
	public $table="mod_exue_order";
	public function __construct(){
		parent::__construct();
	}
	public function getStatus($status){
		if($status==0){
			return "待报名";
		}elseif($status==1){
			return "已报名";
		}elseif($status==2){
			return "学习中";
		}elseif($status==3){
			return "已完成";
		}elseif($status==4){
			return "已结束";
		}
	}
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if($res){
			foreach($res as $rs){
				$uids[]=$rs["userid"];
			}
			 
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($res as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$res[$k]=$v;
			}
			return $res;
		}
	}
	
	public function Add($ops){
		$userid=intval($ops["userid"]);
		$courseid=intval($ops["courseid"]);
		$course=M("mod_exue_course")->selectRow("courseid=".$courseid);
		$per=MM("exue","exue_shop_commission")->get($course["shopid"]);
		$money=floatval($ops["money"]); 
		$pt_money=$money*(100-$per["per"])/100;
		if($course["stype"]==1){
			$pt_money=0;
		}
		$nickname=sql($ops["nickname"]);
		$telephone=sql($ops["telephone"]);
		$this->insert(array(
			"userid"=>$userid,
			"courseid"=>$courseid,
			"nickname"=>$nickname,
			"telephone"=>$telephone,
			"createtime"=>date("Y-m-d H:i:s"),
			"shopid"=>$course["shopid"],
			"money"=>$money,
			"pt_money"=>$pt_money,
			"ispay"=>1
		));
	}
	
	public function BaoMing($orderid,$order=array()){
		if(empty($order)){
			$order=$this->selectRow("orderid=".$orderid);
		}
		$this->update(array(
			"status"=>2,
			"bm_time"=>date("Y-m-d H:i:s")
		),"orderid=".$orderid);
		//给商家钱
		MM("exue","exue_shop_money")->addMoney(array(
			"income"=>$order["money"],
			"balance"=>$order["balance"],
			"content"=>"有人报名咯"
		));
	}
	
}