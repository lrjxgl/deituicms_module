<?php
class ershou_homeControl extends skymvc{
	
	public function onDefault(){
		$userid=get("userid","i");
		$this->smarty->goAssign(array(
			"userid"=>$userid
		));
		$this->smarty->display("ershou_home/index.html");
	}
	public function onUser(){
		$userid=get("userid","i");
		$user=M("user")->getUser($userid);
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$this->goAll("success",0,array(
			"user"=>$user,
			"shop"=>$shop
		));
	}
	
	public function onProduct(){
		$userid=get("userid","i");
		$shop=MM("ershou","ershou_shop")->getShopByUserid($userid);
		$start=get("per_page","i");
		$limit=12;
		$rscount=true;
		$list=MM("ershou","ershou_product")->Dselect(array(
			"where"=>" userid=".$userid." AND status=1 ",
			"order"=>" productid DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->goAll("success",0,array(
			"list"=>$list,
			"per_page"=>$per_page,
			"rscount"=>$rscount
		));
	}
}