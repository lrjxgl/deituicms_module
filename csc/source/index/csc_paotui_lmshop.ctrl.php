<?php
class csc_paotui_lmshopControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	 
	public function shopNum(){
		$userid=M("login")->userid;
		$res=M("mod_csc_paotui_lmshop_cart")->select(array(
			"where"=>" userid=".$userid."  AND shopid=".SHOPID." group By lmid ",
			"fields"=>"lmid,sum(num) as num",
		));
		if($res){
			$list=array();
			foreach($res as $v){
				$list[$v["lmid"]]=$v["num"];
			}
			return $list;
		}
	}
	public function cartNum(){
		$userid=M("login")->userid;
		$res=M("mod_csc_paotui_lmshop_cart")->select(array(
			"where"=>" userid=".$userid." AND shopid=".SHOPID,
			"fields"=>"productid,num"
		));
		if($res){
			$list=array();
			foreach($res as $v){
				$list[$v["productid"]]=$v["num"];
			}
			return $list;
		}
	}
	public function cartMoney(){
		$userid=M("login")->userid;
		$res=M("mod_csc_paotui_lmshop_cart")->select(array(
			"where"=>" userid=".$userid." AND shopid=".SHOPID,
			"fields"=>"productid,num"
		));
		$money=0;
		if($res){
			$ids=array();
			foreach($res as $v){
				$ids[]=$v["productid"];
			}
			$pros=M("mod_csc_paotui_lmshop_product")->select(array(
				"where"=>" productid in("._implode($ids).") ",
				"fields"=>"productid,price"
			));
			if($pros){
				foreach($pros as $p){
					$plist[$p["productid"]]=$p;
				}
			}
			
			foreach($res as $rs){
				if(isset($plist[$rs["productid"]])){
					$money+=$plist[$rs["productid"]]["price"]*$rs["num"];
				}
			}
			
		}
		return $money;
	}
	public function onDefault(){
		 
		$list=M("mod_csc_paotui_lmshop")->select(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>" lmid,shopname "
		));
		$lmid=get("lmid","i");
		if($list){
			$sps=$this->shopNum();
			foreach($list as $k=>$v){
				if(isset($sps[$v["lmid"]])){
					$v["num"]=$sps[$v["lmid"]];
				}else{
					$v["num"]=0;
				}
				
				$list[$k]=$v;
			}
			if($lmid==0){
				$lmid=$list[0]["lmid"];
			}
			
			$proList=M("mod_csc_paotui_lmshop_product")->select(array(
				"where"=>" lmid=".$lmid,
				"order"=>" orderindex ASC"
			));
			if($proList){
				$cps=$this->cartNum();
				foreach($proList as $k=>$v){
					if(isset($cps[$v["productid"]])){
						$v["cartnum"]=$cps[$v["productid"]];
					}else{
						$v["cartnum"]=0;
					}
					
					$proList[$k]=$v;
				}
			}
		}
		$totalMoney=$this->cartMoney();
		$this->smarty->goAssign(array(
			"list"=>$list,
			"proList"=>$proList,
			"lmid"=>$lmid,
			"totalMoney"=>$totalMoney
		));
		$this->smarty->display("csc_paotui_lmshop/index.html");
	}
	
}
?>