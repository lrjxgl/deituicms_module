<?php
class flk_shop_moneyModel extends model{
	
	public $table="mod_flk_shop_money";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid){
		$row=$this->selectRow("shopid=".$shopid);
		if(!$row){
			$this->insert(array(
				"shopid"=>$shopid
			));
			$row=$this->selectRow("shopid=".$shopid);
		}
		return $row;
	}
	/**
	 * income 收入
	 * balance 可用余额
	 */
	public function addMoney($ops){
		$shopid=intval($ops['shopid']);
		$ops["income"]=floatval($ops["income"]);
		$ops["balance"]=floatval($ops["balance"]);
		$row=$this->get($shopid);
		$this->begin();
		$time=date("Y-m-d H:i:s");
		$bc=$ops["content"].",之前".$row["balance"].",现在".($row["balance"]+$ops["balance"]);
		$this->update(array(
			"income"=>$row["income"]+$ops["income"],
			"balance"=>$row["balance"]+$ops["balance"]
		),"shopid=".$shopid);
		M("mod_flk_shop_money_log")->insert(array(
			"shopid"=>$shopid,
			"createtime"=>$time,
			"money"=>$ops["balance"],
			"content"=>$bc
		));
		$this->commit();
	}
	
	public function payMoney($ops){
		$userid=intval($ops["userid"]);
		$shopid=intval($ops['shopid']);
		$money=floatval($ops["money"]);
		$flk_money=0;
		if(isset($ops["flk_money"])){
			$flk_money=$ops["flk_money"];
		}	
		$shop=M("mod_flk_shop")->selectRow(array(
			"where"=>"shopid=".$shopid,
			"fields"=>"shopid,flk_discount,flk_maxmoney,flk_new,status,express_money"
		));
		$shop_money=($money-$flk_money)*(1-$shop["flk_discount"]);
		$ops["content"]="用户扫码支付".$money.",到款".$shop_money."元，";
		$ops["income"]=$shop_money;
		$ops["balance"]=$shop_money;
		$row=$this->get($shopid);
		$this->begin();
		$time=date("Y-m-d H:i:s");
		$bc=$ops["content"].",之前".$row["balance"].",现在".($row["balance"]+$ops["balance"]);
		$this->update(array(
			"income"=>$row["income"]+$ops["income"],
			"balance"=>$row["balance"]+$ops["balance"]
		),"shopid=".$shopid);
		M("mod_flk_shop_money_log")->insert(array(
			"shopid"=>$shopid,
			"createtime"=>$time,
			"money"=>$ops["balance"],
			"content"=>$bc
		));
		//返给消费者
		if(isset($ops["flk_money"])){
			$flk_money=$ops["flk_money"];
			M("mod_flk_queue")->insert(array(
				"shopid"=>$shopid,
				"ischeck"=>1,
				"dateline"=>time(),
				"userid"=>$userid,
				"total_money"=>$shop_money,
				"money"=>$shop_money,
				"flk_money"=>$flk_money,
				"orderid"=>0,
			));
		}
		$bmoney=($money-$flk_money)*$shop["flk_discount"]/100;
		if($flk_money==0){
			$bmoney=$bmoney/2;
		}
		$buserid=0;
		 
		MM("flk","flk_queue")->backMoney($shopid,$bmoney,$buserid);
		$this->commit();
	}
	
}
?>