<?php
class mmjz_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$shopid=get("shopid","i");
		$shop=MM("mmjz","mmjz_shop")->get($shopid);
		if(empty($shop)){
			$this->goAll("商家不存在",1);
		}
		$userid=M("login")->userid;
		MM("mmjz","mmjz_view_user")->add($shopid,$userid);
		//商家促销
		$spid=get("spid","i");
		if($spid){
			MM("mmjz","mmjz_shop_hotvip")->addUser(array(
				"spid"=>$spid,
				"shopid"=>$shopid,
				"userid"=>$userid
			));
		}
		
		//收藏
		$isfav=0;
		$couponList=null;
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_mmjz_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			$couponList=MM("mmjz","mmjz_coupon")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 "
			));
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList
		));
		if($shop["yystatus"]!=1){
			$this->smarty->display("mmjz_shop/base/index.html");
		}else{
			$this->smarty->display("mmjz_shop/index/index.html");
		}
		
	}
	
	public function onRecommend(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$where=" shopid=".$shopid." AND status=1  AND isrecommend=1";
		$list=MM("mmjz","mmjz_product")->Dselect(array(
			"where"=>$where
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$shop=MM("mmjz","mmjz_shop")->get($shopid);
		$shop_catlist=MM("mmjz","mmjz_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("mmjz","mmjz_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}
		$list=MM("mmjz","mmjz_product")->Dselect(array(
			"where"=>$where
		));
		$this->smarty->goAssign(array(
			"shop_catlist"=>$shop_catlist,
			"list"=>$list,
			"cart_num"=>1
		));
	}
	public function onRaty(){
		$shopid=get("shopid","i");
		$shop=MM("mmjz","mmjz_shop")->get($shopid);
		$ratyList=MM("mmjz","mmjz_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("mmjz_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=get("shopid","i");
		$shop=MM("mmjz","mmjz_shop")->get($shopid);
		 
		//证件
		$certList=MM("mmjz","mmjz_shop_cert")->Dselect(array(
			"where"=>"shopid=".$shopid
		));
		
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"zzimgsdata"=>$zzimgsdata,
			"certList"=>$certList
		));
		$this->smarty->display("mmjz_shop/index/index.html");
	}
}