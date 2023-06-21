<?php
class flk_oneControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("flk_one/index.html");
	}
	public function onShow(){
		$id=get("id","i");
		$userid=M("login")->userid;
		$data=M("mod_flk_product")->selectRow("id=".$id);
		$canbuy=1;
		if(time()>strtotime($data["one_etime"])){
			$canbuy=2;
		}elseif(time()<strtotime($data["one_stime"])){
			$canbuy=0;
		}
		 
		$data["imgurl"]=images_site($data["imgurl"]);
		$data["content"]=M("mod_flk_product_data")->selectOne(array(
			"where"=>"id=".$id,
			"fields"=>"content"
		));
		$data["ez_price"]=$data["one_price"]*0.1;
		$data["ez_sx_price"]=$data["one_price"]*100*0.9/100;
		$shop=MM("flk","flk_shop")->get($data["shopid"]);
		//返利金库支付
		$total_money=$data["one_price"];
		$account_money=MM("flk","flk_account")->get($userid);
		if($account_money>=$total_money){
			$pay_money=0;
		}else{
			$pay_money=$total_money-$account_money;
		}
			 
		$pay_money=ceil($pay_money*100)/100;
		$addr=M("user_lastaddr")->get($userid);
		//分享链接
		$shareLink=HTTP_HOST."/module.php?m=flk_one&a=show&id=".$id;
		if($userid){
			$queue=M("mod_flk_queue")->selectOne(array(
				"where"=>" ordertype='one' AND productid=".$id." AND userid=".$userid." AND ischeck=1 AND isfinish=0   ",
				"order"=>" dateline ASC",
				"fields"=>"orderid"
			));
			if($queue){
				$shareLink.="&set_invite_orderid=".$queue;
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data,
			"shop"=>$shop,
			"canbuy"=>$canbuy,
			"pay_money"=>$pay_money,
			"account_money"=>$account_money,
			"addr"=>$addr,
			"shareLink"=>$shareLink
		));
		$this->smarty->display("flk_one/show.html");
	}
	public function onList(){
		
		$where=" status=1 AND one_status=1 AND one_on=1 ";
		$day=date("Y-m-d H:i:s");
		switch(get("type")){
			case "doing":
				$where.="AND one_stime<='".$day."' AND one_etime>='".$day."' ";
				break;
			case "will":
				$where.="AND one_stime>'".$day."' ";
				break;
			case "done":
				$where.="AND one_etime<'".$day."' ";
				break;
		}
		$list=MM("flk","flk_product")->Dselect(array(
			"where"=>$where,
			"limit"=>100
		));
		 
		if($list){
			foreach($list as $v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("flk","flk_shop")->getListByIds($shopids,"shopid,shopname,imgurl");
			foreach($list as $k=>$v){
				if(time()>strtotime($v["one_stime"]) && time()<strtotime($v["one_etime"])){
					$v["canbuy"]=1;
				}else{
					$v["canbuy"]=0;
				}
				$v["ez_price"]=$v["one_price"]*0.1;
				$v["fan_price"]=$v["one_price"]*0.9;
				$v["shop"]=$sps[$v["shopid"]]; 
				$list[$k]=$v;
			}
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list
			)
		));
	}
	
	public function onQueue(){
		$productid=get("productid","i");
		$ops=array(
			"where"=>" ordertype='one' AND ischeck=1 AND productid=".$productid,
			"order"=>"dateline ASC"
		);
		$list=M("mod_flk_queue")->select($ops);
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$list[$k]=$v;
			}
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"list"=>$list
			)
		));
	}
	
	 
	
}
?>