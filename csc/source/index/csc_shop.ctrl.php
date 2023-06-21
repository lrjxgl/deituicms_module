<?php
class csc_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$shopid=get("shopid","i");
		$shop=MM("csc","csc_shop")->get($shopid);
		$userid=M("login")->userid;
		MM("csc","csc_view_user")->add($shopid,$userid);
		$isfav=0;
		$couponList=null;
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_csc_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			$couponList=MM("csc","csc_coupon")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 "
			));
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList
		));
		$this->smarty->display("csc_shop/index/index.html");
	}
	
	public function onRecommend(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$where=" shopid=".$shopid." AND status=1  AND isrecommend=1";
		$list=MM("csc","csc_product")->Dselect(array(
			"where"=>$where
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$shop=MM("csc","csc_shop")->get($shopid);
		$shop_catlist=MM("csc","csc_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("csc","csc_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}
		$list=MM("csc","csc_product")->Dselect(array(
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
		$shop=MM("csc","csc_shop")->get($shopid);
		$ratyList=MM("csc","csc_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("csc_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=get("shopid","i");
		$shop=MM("csc","csc_shop")->get($shopid);
		//资质文件
		$zzimgsdata=array();
		if(!empty($data['zzdata'])){
			$zzs=explode(",",$data['zzdata']);
			
			if(!empty($zzs)){
				foreach($zzs as $v){
					$zzimgsdata[]=images_site($v);
				}
			}
		}
		
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"zzimgsdata"=>$zzimgsdata,
		));
		$this->smarty->display("csc_shop/index/index.html");
	}
}