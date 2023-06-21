<?php
class shopsiteControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		
		$shopid=SHOPID;
		$shop=MM("b2b","b2b_shop")->get($shopid);
		$userid=M("login")->userid;
		MM("b2b","b2b_view_user")->add($shopid,$userid);
		$isfav=0;
		$couponList=null;
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_b2b_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			$couponList=MM("b2b","b2b_coupon")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 "
			));
		}
		$shop_catlist=MM("b2b","b2b_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList,
			"shop_catlist"=>$shop_catlist,
		));
		$this->smarty->display("index/index.html");
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=SHOPID;
		$shop=MM("b2b","b2b_shop")->get($shopid);
		$shop_catlist=MM("b2b","b2b_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("b2b","b2b_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}else{
			$where.=" AND isrecommend=1 ";
		}
		$list=MM("b2b","b2b_product")->Dselect(array(
			"where"=>$where
		));
		$this->smarty->goAssign(array(
			"shop_catlist"=>$shop_catlist,
			"list"=>$list,
			"cart_num"=>1
		));
	}
	public function onRaty(){
		$shopid=SHOPID;
		$shop=MM("b2b","b2b_shop")->get($shopid);
		$ratyList=MM("b2b","b2b_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("b2b_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=SHOPID;
		$shop=MM("b2b","b2b_shop")->get($shopid);
	 
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
		$this->smarty->display("shopsite/detail.html");
	}
}

?>