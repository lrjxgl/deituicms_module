<?php
class gxny_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$shopid=MM("gxny","gxny_shop")->inShopid();
		
		$shop=MM("gxny","gxny_shop")->get($shopid);
		if(empty($shop)){
			$this->goAll("基地已经下线了",1);
		}
		$userid=M("login")->userid;
		MM("gxny","gxny_view_user")->add($shopid,$userid);
		$isfav=0;
		$couponList=null;
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_gxny_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			 
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList,
			"shopid"=>$shopid
		));
		$this->smarty->display("gxny_shop/index.html");
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=MM("gxny","gxny_shop")->inShopid();
		$shop=MM("gxny","gxny_shop")->get($shopid);
		$shop_catlist=MM("gxny","gxny_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("gxny","gxny_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}
		$list=MM("gxny","gxny_product")->Dselect(array(
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
		$shop=MM("gxny","gxny_shop")->get($shopid);
		$ratyList=MM("gxny","gxny_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("gxny_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=get("shopid","i");
		$shop=MM("gxny","gxny_shop")->get($shopid);
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
		$this->smarty->display("gxny_shop/index/index.html");
	}
	
	public function onSetshop(){
		$shopid=get("shopid","i");
		$_SESSION["ss_gxny_shopid"]=$shopid;
		setcookie("ck_gxny_shopid",$shopid,3600*24*360,"/",DOMAIN);
		echo json_encode(array(
			"error"=>0,
			"message"=>"success"
		));
	}
}