<?php
class wmo2o_shop_hotvipControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("wmo2o_shop_hotvip/index.html");
	}
	
	public function onShow(){
		$spid=get("spid","i");
		$list=MM("wmo2o","wmo2o_shop_hotvip_user")->select(array(
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
		$this->smarty->display("wmo2o_shop_hotvip/show.html");
	}
	
	public function onMy(){
		$sql=" select b.title,a.* 
			from ".table("mod_wmo2o_shop_hotvip")." as a 
			left join ".table('mod_wmo2o_hotvip')." as b  
			on a.vid=b.vid 
			where a.shopid=".SHOPID." 
			order by a.spid DESC 
		";
		$list=M("mod_wmo2o_shop_hotvip")->getAll($sql);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("wmo2o_shop_hotvip/index.html");
	}
	public function onBuy(){
		$vid=get("vid","i");
		$vip=M("mod_wmo2o_hotvip")->selectRow("vid=".$vid." AND status=1");
		if(empty($vip)){
			$this->goAll("产品已下架",1);
		}
		$shopmoney=MM("wmo2o","wmo2o_shop_money")->get(SHOPID);
		if($shopmoney["balance"]<$vip["price"]){
			$this->goAll("余额不足",1);
		}
		MM("wmo2o","wmo2o_shop_money")->begin();
		MM("wmo2o","wmo2o_shop_money")->addMoney(array(
			"balance"=>-$vip["price"],
			"shopid"=>SHOPID,
			"content"=>"购买".$vip["title"]
		));
		M("mod_wmo2o_shop_hotvip")->insert(array(
			"vid"=>$vid,
			"shopid"=>SHOPID,
			"price"=>$vip["price"],
			"num"=>$vip["num"],
			"createtime"=>date("Y-m-d H:i:s")
		));
		MM("wmo2o","wmo2o_shop_money")->commit();
		$this->goAll("购买成功");
	}
	
}