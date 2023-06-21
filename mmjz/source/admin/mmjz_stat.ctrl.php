<?php
class mmjz_statControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		session_write_close();
	}
	public function onDefault(){
		 
		$this->smarty->display("mmjz_stat/index.html");
	}
	
	public function onData(){
		$time=strtotime(date("Y-m-d 00:00:00"));
		for($i=7;$i>=0;$i--){
			$labels[]=date("m月d",$time-$i*3600*24);
		}
		for($i=7;$i>=0;$i--){
			$h=date("Y-m-d",$time-$i*3600*24);
		 
			$sql="select COUNT(*) from ".table('mod_mmjz_order')." where   createtime like '".$h."%'";
			 
			$orderMoneys[]=$count=M("mod_mmjz_order")->getOne($sql);
			 
		}
		$this->smarty->goAssign(array(
			"labels"=>$labels,
			
			"orderMoneys"=>$orderMoneys,
			"total"=>$total
		));
		$this->smarty->display("mmjz_stat/index.html");
	}
	
	public function onAdminIndexStat(){
		//订单总额
		$order_money=M("mod_mmjz_order")->selectOne(array(
			"where"=>" status in(1,2,3) ",
			"fields"=>"sum(money)"
		));
		$order_money=$order_money<=0?0:$order_money;
		$pay_money=M("recharge")->selectOne(array(
			"where"=>" status=1 ",
			"fields"=>"sum(money)"
		));
		$user_num=M("user")->selectOne(array(
			"fields"=>"count(*)"
		));
		$shop_num=M("mod_mmjz_shop")->selectOne(array(
			"where"=>" status=1 ",
			"fields"=>"count(*)"
		));
		$product_num=M("mod_mmjz_product")->selectOne(array(
			"where"=>" status=1 ",
			"fields"=>"count(*)"
		));
		$this->goAll("success",0,array(
			"order_money"=>$order_money,
			"user_num"=>$user_num,
			"shop_num"=>$shop_num,
			"product_num"=>$product_num,
			"pay_money"=>$pay_money
		));
		
	}
	
}