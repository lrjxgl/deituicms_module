<?php
class flk_queueModel extends model{
	public $table="mod_flk_queue";
	public function __construct(){
		parent::__construct();
	}
	
	public function statUser($userid){
		$flks=M("mod_flk_queue")->selectRow(array(
			"where"=>" userid=".$userid." AND ischeck=1 ",
			"fields"=>" sum(back_money) as back_money,sum(money) as money "
		));
		if(empty($flks["back_money"])){
			$flks=array(
				"back_money"=>0,
				"money"=>0
			);
		}
		$flk_num=M("mod_flk_queue")->selectOne(array(
			"where"=>" userid=".$userid." AND ischeck=1 AND isfinish=0 ",
			"fields"=>" count(*) as num "
		));
		return array(
			"flks"=>$flks,
			"flk_num"=>$flk_num
		);
	}
	
	public function statAll(){
		$flks=M("mod_flk_queue")->selectRow(array(
			"where"=>"  ischeck=1 ",
			"fields"=>" sum(back_money) as back_money,sum(money) as money "
		));
		
		if(empty($flks["back_money"])){
			$flks=array(
				"back_money"=>0,
				"money"=>0
			);
		}
		$flk_num=M("mod_flk_queue")->selectOne(array(
			"where"=>" ischeck=1 AND isfinish=0 ",
			"fields"=>" count(*) as num "
		));
		return array(
			"flks"=>$flks,
			"flk_num"=>$flk_num
		);
	}
	public function statShop($shopid){
		$flks=M("mod_flk_queue")->selectRow(array(
			"where"=>" shopid=".$shopid." AND ischeck=1 ",
			"fields"=>" sum(back_money) as back_money,sum(money) as money "
		));
		if(empty($flks["back_money"])){
			$flks=array(
				"back_money"=>0,
				"money"=>0
			);
		}
		$flk_num=M("mod_flk_queue")->selectOne(array(
			"where"=>" shopid=".$shopid." AND ischeck=1 AND isfinish=0 ",
			"fields"=>" count(*) as num "
		));
		return array(
			"flks"=>$flks,
			"flk_num"=>$flk_num
		);
	}
	
	public function backMoney($shopid,$money,$userid=0){
		if($userid){
			$row=$this->selectRow(array(
				"where"=>" shopid=".$shopid." AND isfinish=0 AND ischeck=1 AND userid=".$userid,
				"order"=>"dateline ASC"
			));
		}else{
			$row=$this->selectRow(array(
				"where"=>" shopid=".$shopid."  AND isfinish=0 AND ischeck=1 ",
				"order"=>"dateline ASC"
			));
		}
		if(empty($row)) return ;
		$mm=max(0,$row["money"]-$money);
		$bmoney=min($row["total_money"],$row["back_money"]+$money);
		$isfinish=0;
		if($mm==0){
			$isfinish=1;
		}
		M("mod_flk_queue_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>$row["shopid"],
			"money"=>$mm==0?$row["money"]:$money,
			"xfrom"=>$userid?"user":"shop",
			"dateline"=>time()
		));
		MM("flk","flk_account")->addMoney(array(
			"userid"=>$row["userid"],
			"money"=>$mm==0?$row["money"]:$money,
			"content"=>"返还".($mm==0?$row["money"]:$money)
		));
		$this->update(array(
			"money"=>$mm,
			"back_money"=>$bmoney,
			"isfinish"=>$isfinish,
			"isback"=>1,
			"status"=>1
		),"id=".$row["id"]);
	}
	
	public function one_backMoney($ops){
		$shopid=$ops["shopid"];
		$userid=$ops["userid"];
		$money=$ops["money"];
		$ordertype=$ops["ordertype"];
		$productid=$ops["productid"];
		$pin_orderid=$ops["pin_orderid"];
		if($userid){
			$row=$this->selectRow(array(
				"where"=>" ordertype='one' AND orderid=".$pin_orderid." AND  shopid=".$shopid." AND isfinish=0 AND ischeck=1 AND userid=".$userid,
				"order"=>"dateline ASC"
			));
			if(empty($row)){
				$row=$this->selectRow(array(
					"where"=>" ordertype='one' AND productid=".$productid." AND  shopid=".$shopid." AND isfinish=0 AND ischeck=1 AND userid=".$userid,
					"order"=>"dateline ASC"
				));
			}
			
		}else{
			$row=$this->selectRow(array(
				"where"=>"ordertype='one' AND productid=".$productid." AND shopid=".$shopid."  AND isfinish=0 AND ischeck=1 ",
				"order"=>"dateline ASC"
			));
		}
		if(empty($row)) return ;
		$mm=max(0,$row["money"]-$money);
		$bmoney=min($row["total_money"],$row["back_money"]+$money);
		$isfinish=0;
		if($mm==0){
			$isfinish=1;
		}
		M("mod_flk_queue_log")->insert(array(
			"userid"=>$row["userid"],
			"shopid"=>$row["shopid"],
			"money"=>$mm==0?$row["money"]:$money,
			"xfrom"=>$userid?"user":"shop",
			"dateline"=>time()
		));
		MM("flk","flk_account")->addMoney(array(
			"userid"=>$row["userid"],
			"money"=>$mm==0?$row["money"]:$money,
			"content"=>"返还".($mm==0?$row["money"]:$money)
		));
		$this->update(array(
			"money"=>$mm,
			"back_money"=>$bmoney,
			"isfinish"=>$isfinish,
			"isback"=>1,
			"status"=>1
		),"id=".$row["id"]);
	}
}