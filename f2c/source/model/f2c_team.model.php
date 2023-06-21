<?php
class f2c_teamModel extends model{
	public $table="mod_f2c_team";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$rss=$this->select(array(
			"where"=>" teamid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				$rs["userhead"]=images_site($rs["userhead"]);
				$data[$rs["teamid"]]=$rs;
				 
			}
			return $data;
		}
	}
	
	/**
	*账户变动
	**/
	public function addMoney($ops){
		
		$teamid=$ops["teamid"];
		$team=MM("f2c","f2c_team")->selectRow("teamid=".$teamid);
		if($ops["money"]>0){
			$typeid=1;
		}else{
			$typeid=2;
		}
		$this->update(array(
			"money"=>$team["money"]+$ops["money"],
			"team_money"=>$team["team_money"]+$ops["team_money"]
		),"teamid=".$teamid);
		$content=$ops["content"].","."之前".$team["money"].",现在".($team["money"]+$ops["money"]);
		M("mod_f2c_team_log")->insert(array(
			"teamid"=>$teamid,
			"money"=>$ops["money"],
			"typeid"=>$typeid,
			"content"=>$content,
			"createtime"=>$ops["createtime"]
		));
	}
	
	
	//根据订单返利
	public function fanli_order($order){
		
		$rankList=M("mod_f2c_config_rank")->select(array(
			"order"=>"min_grade ASC"
		));
		if(empty($rankList)){
			return false;
		}
		$maxStep=count($rankList);
		$uids=M("user")->getParentsIds($order['userid'],$maxStep);
	
		if(!$uids){
			return false;
		}
		
		$us=$this->getListByUids($uids);
		if(empty($us)){
			return false;
		}
		$disuser=array();
		$lastdiscount=0;
		foreach($us as $uid=>$ur){
			$discount=$this->rankDiscount($rankList,$us[$uid]["team_money"]);
			$disuser[$uid]=$discount-$lastdiscount;
			$lastdiscount=$discount;
		}
		//返利给团队
		$finishtime=date("Y-m-d H:i:s");
		foreach($disuser as $userid=>$discount){
			$balance=$order["money"]*$discount/100;
			$this->addMoney(array(
				"teamid"=>$us[$userid]["teamid"],
				"money"=>$balance,
				"team_money"=>$order["money"],
				"createtime"=>$finishtime,
				"content"=>"订单完成获得了".$balance."元"
			));
			MM("f2c","f2c_done")->add(array(
				"teamid"=>$us[$userid]["teamid"],
				"income"=>$balance, 
				"money"=>$order["money"],
				"smonth"=>date("Ym",strtotime($finishtime)),
				"createtime"=>$finishtime,
			));
		}
		
	}
	
	public function fanli_product($order){
		$orderid=$order["orderid"];
		$rankList=M("mod_f2c_config_rank")->select(array(
			"order"=>"min_grade ASC"
		));
		if(empty($rankList)){
			return false;
		}
		$maxStep=count($rankList);
		$uids=M("user")->getParentsIds($order['userid'],$maxStep);
		
		if(!$uids){
			return false;
		}
		
		$us=$this->getListByUids($uids);
		if(empty($us)){
			return false;
		}
		$disuser=array();
		$lastdiscount=0;
		//获取订单产品
		$sql="select p.id,p.per_money,op.price,op.amount,p.fanli from ".table("mod_f2c_order_product")." as op 
			left join ".table("mod_f2c_product")." as p 
			on op.productid=p.id
			where op.orderid=".$orderid."
		";
		$res=MM("f2c","f2c_product")->getAll($sql);
		//根据每个产品计算
		$dismoney=array();
		foreach($res as $rs){
			$fls=explode(",",$rs["fanli"]);
			//计算阶梯返利
			foreach($rankList as $k=>$v){
				$v["discount"]=$fls[$k];
				$rankList[$k]=$v;
			}
			//计算所有用户返利
			$disuser=array();
			foreach($us as $uid=>$ur){
				$discount=$this->rankDiscount($rankList,$us[$uid]["team_money"]);
				$disuser[$uid]=$discount-$lastdiscount;
				$lastdiscount=$discount;
			}
			//计算返利金额
			foreach($disuser as $userid=>$discount){
				$dmoney=$rs["price"]*$rs["amount"]*$discount/100;
				if(isset($dismoney[$userid])){
					$dismoney[$userid]+=$dmoney;
				}else{
					$dismoney[$userid]=$dmoney;
				}
				
			}	
		}
		//返利给团队
		$finishtime=date("Y-m-d H:i:s");
		foreach($dismoney as $userid=>$balance){
			 
			$this->addMoney(array(
				"teamid"=>$us[$userid]["teamid"],
				"money"=>$balance,
				"team_money"=>$order["money"],
				"createtime"=>$finishtime,
				"content"=>"订单完成获得了".$balance."元"
			));
			MM("f2c","f2c_done")->add(array(
				"teamid"=>$us[$userid]["teamid"],
				"income"=>$balance, 
				"money"=>$order["money"],
				"smonth"=>date("Ym",strtotime($finishtime)),
				"createtime"=>$finishtime,
			));
		}
	}
	public function rankDiscount($rankList,$money){
		foreach($rankList as $v){
			if($v["min_grade"]<=$money && $v["max_grade"]>$money){
				return $v["discount"];
			}
		}
	}
	public function getListByUids($uids){
		$res=$this->select(array(
			"where"=>" userid in("._implode($uids).") ",
			"fields"=>"team_money,userid,teamid"
		));
		if(!$res){
			return false;
		}
		$us=array();
		foreach($res as $v){
			$us[$v["userid"]]=$v;
		}
		return $us;
	}
}