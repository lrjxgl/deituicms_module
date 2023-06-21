<?php
class mmjz_shop_hotvipControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("mmjz_shop_hotvip/index.html");
	}
	
	public function onShow(){
		$spid=get("spid","i");
		$list=MM("mmjz","mmjz_shop_hotvip_user")->select(array(
			"where"=>"spid=".$spid
		));
		if(!empty($list)){
			$uids=[];
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("mmjz_shop_hotvip/show.html");
	}
	
	public function onMy(){
		$sql=" select b.title,a.* 
			from ".table("mod_mmjz_shop_hotvip")." as a 
			left join ".table('mod_mmjz_hotvip')." as b  
			on a.vid=b.vid 
			where a.shopid=".SHOPID." 
			order by a.spid DESC 
		";
		$list=M("mod_mmjz_shop_hotvip")->getAll($sql);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("mmjz_shop_hotvip/index.html");
	}
	public function onBuy(){
		$vid=get("vid","i");
		$vip=M("mod_mmjz_hotvip")->selectRow("vid=".$vid." AND status=1");
		if(empty($vip)){
			$this->goAll("产品已下架",1);
		}
		$shopmoney=MM("mmjz","mmjz_shop_money")->get(SHOPID);
		if($shopmoney["balance"]<$vip["price"]){
			$this->goAll("余额不足",1);
		}
		MM("mmjz","mmjz_shop_money")->begin();
		MM("mmjz","mmjz_shop_money")->addMoney(array(
			"balance"=>-$vip["price"],
			"shopid"=>SHOPID,
			"content"=>"购买".$vip["title"]
		));
		M("mod_mmjz_shop_hotvip")->insert(array(
			"vid"=>$vid,
			"shopid"=>SHOPID,
			"price"=>$vip["price"],
			"num"=>$vip["num"],
			"createtime"=>date("Y-m-d H:i:s")
		));
		MM("mmjz","mmjz_shop_money")->commit();
		$this->goAll("购买成功");
	}
	
}