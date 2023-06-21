<?php
class taoke_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$type=get("type","h");
		switch($type){
			case "cancel":
				$where.=" AND orderstatus in('已取消') ";
				break;
			case "ispay":
				$where.=" AND orderstatus in('已付款') ";
				break;
			case "finish":
				$where.=" AND orderstatus in('已结算') ";
				break;
		}
		$list=M("mod_taoke_order")->select(array(
			"where"=>$where,
			"order"=>" orderid DESC"
		));
	 
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("taoke_order/my.html");
	}
	
	public function onBaoDan(){
		
		$this->smarty->display("taoke_order/baodan.html");
	}
	public function onBaoDanSave(){
		$orderno=post("orderno","h");
		$money=post("money","h");
		$userid=M("login")->userid;		
		$xfrom=post("xfrom","h");
		if(empty($xfrom)){
			$xfrom="taobao";
		}
		if(empty($orderno)){
			$this->goAll("请填写订单号",1);
		}
		if($money==0){
			//$this->goAll("请填写订单金额",1);
		}
		$row=M("mod_taoke_baodan")->selectRow("orderno='".$orderno."' AND xfrom='".$xfrom."' ");
		if($row){
			$this->goAll("该订单已经有人报过了",1);
		}
		$order=M("mod_taoke_order")->selectRow("k='".$xfrom."' AND orderno='".$orderno."'");
		$status=0;
		switch($xfrom){
			case "pdd":
				if($order){
					$this->goAll("该订单已经报单过了",1);
				}
				$status=1;
				$res=file_get_contents(HTTP_HOST."/module.php?m=taoke_pdd_search&a=getorder&orderno=".$orderno);
				$json=json_decode($res,true);
				$pddOrder=$json["data"];
				if($json["error"]){
					$this->goAll("该订单还未生效，请稍后再试",1);
				}
				M("mod_taoke_baodan")->insert(array(
					"userid"=>$userid,
					"money"=>$pddOrder["order_amount"]/100,
					"orderno"=>$orderno,
					"xfrom"=>$xfrom,
					"status"=>$status,
					"dateline"=>time()
				));
				M("mod_taoke_order")->insert(array(
					"userid"=>$userid,
					"title"=>$pddOrder["goods_name"],
					"imgurl"=>$pddOrder["goods_thumbnail_url"],
					"productid"=>$pddOrder["goods_id"],
					"createtime"=>date("Y-m-d H:i:s",$pddOrder["order_create_time"]),
					"orderno"=>$orderno,
					"money"=>$pddOrder["order_amount"]/100,
					"income"=>$pddOrder["promotion_amount"]/100,
					"orderstatus"=>$pddOrder["order_status_desc"],
					"k"=>$xfrom,
					"isbd"=>1
				));
				
				
				break;
			default:
					
					M("mod_taoke")->begin();
					if($order){
						$status=1;
						M("mod_taoke_order")->update(array(
							"userid"=>$userid
						),"orderno='".$orderno."' ");
					}
					M("mod_taoke_baodan")->insert(array(
						"userid"=>$userid,
						"money"=>$money,
						"orderno"=>$orderno,
						"xfrom"=>$xfrom,
						"status"=>$status,
						"dateline"=>time()
					));
					M("mod_taoke")->commit();
				break;
		}
		
		$this->goAll("报单成功，请等待审核");
	}
	
}