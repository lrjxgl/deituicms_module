<?php
class pdd_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$shopid=get("shopid","i");
		$shop=MM("pdd","pdd_shop")->get($shopid);
		$userid=M("login")->userid;
		MM("pdd","pdd_view_user")->add($shopid,$userid);
		$isfav=0;
		$couponList=null;
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_pdd_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			$couponList=MM("pdd","pdd_coupon")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 "
			));
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList
		));
		$this->smarty->display("pdd_shop/index/index.html");
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$shop=MM("pdd","pdd_shop")->get($shopid);
		$shop_catlist=MM("pdd","pdd_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("pdd","pdd_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}
		$list=MM("pdd","pdd_product")->Dselect(array(
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
		$shop=MM("pdd","pdd_shop")->get($shopid);
		$ratyList=MM("pdd","pdd_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("pdd_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=get("shopid","i");
		$shop=MM("pdd","pdd_shop")->get($shopid);
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
		$this->smarty->display("pdd_shop/index/index.html");
	}
	
	public function onPinList(){
		$shopid=get("shopid","i");
		$shop=MM("pdd","pdd_shop")->get($shopid);
		$pts=M("mod_pdd_order")->select(array(
			"where"=>" shopid=".$shopid." AND ispin=1 AND status=0 AND pin_success=0 AND createtime>'".$ctime."'",
			"fields"=>" orderid,productid,userid,pin_num,createtime "
		));
		 
		if($pts){
			foreach($pts as $k=>$v){
				$proids[]=$v["productid"];
				$uids[]=$v["userid"];
			}
			$pros=MM("pdd","pdd_product")->getListByIds($proids);
			$us=M("user")->getUserByIds($uids);
			foreach($pts as $k=>$v){
				$v["product"]=$pros[$v["productid"]];
				$v["user"]=$us[$v["userid"]];
				$v["timego"]=strtotime($v["createtime"])+24*3600-time();
				$pts[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"pts"=>$pts
		));
		$this->smarty->display("pdd_shop/index/pinlist.html");
	}
}