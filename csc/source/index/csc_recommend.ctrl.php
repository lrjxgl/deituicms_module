<?php
class csc_recommendControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$userid=M("login")->userid;
		$cartPros=MM("csc","csc_cart")->getProductAmount("userid=".$userid." AND ksid=0");
		$pids=M("mod_csc_order_product")->selectCols(array(
			"where"=>" shopid=".SHOPID." AND userid=".$userid,
			"fields"=>" productid ",
			"order"=>"id DESC"
		));
		if($pids){
			$pids=array_unique($pids);
			$pids=array_slice($pids,0,48);
			$list=MM("csc","csc_product")->getListByIds($pids);
		}else{	
			$list=MM("csc","csc_product")->Dselect(array(
				"where"=>" shopid=".SHOPID." AND status=1 AND isrecommend=1",
				"order"=>" buy_num DESC",
				"limit"=>48
			));
		}
		if($list){
			foreach($list as $k=>$v){
				$v["incart"]=0;
				$v["cart_amount"]=0;
				$v["imgurl"]=images_site($v["imgurl"]);
				if($cartPros && isset($cartPros[$v["id"]])){
					$v["incart"]=1;
					$v["cart_amount"]=$cartPros[$v['id']];
				} 
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("csc_recommend/index.html");
	}
	
}
?>