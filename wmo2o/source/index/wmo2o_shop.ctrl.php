<?php
class wmo2o_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$shopid=get("shopid","i");
		$shop=MM("wmo2o","wmo2o_shop")->get($shopid);
		$userid=M("login")->userid;
		MM("wmo2o","wmo2o_view_user")->add($shopid,$userid);
		
		//商家促销
		$hotvip=get("hotvip","i");
		if($hotvip){
			$row=MM("wmo2o","wmo2o_shop_hotvip")->selectRow(array(
				"where"=>" shopid=".$shopid." AND has_num>0",
				"order"=>" spid ASC"
			));
			if($row){
				MM("wmo2o","wmo2o_shop_hotvip")->addUser(array(
					"spid"=>$row["spid"],
					"shopid"=>$shopid,
					"userid"=>$userid
				));
			}
			
		}
		$isfav=0;
		$couponList=[];
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_wmo2o_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			$couponList=MM("wmo2o","wmo2o_coupon")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 "
			));
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList
		));
		$this->smarty->display("wmo2o_shop/index/index.html");
	}
	
	public function onRecommend(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$where=" shopid=".$shopid." AND status=1  AND isrecommend=1";
		$list=MM("wmo2o","wmo2o_product")->Dselect(array(
			"where"=>$where
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$shop=MM("wmo2o","wmo2o_shop")->get($shopid);
		$shop_catlist=MM("wmo2o","wmo2o_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("wmo2o","wmo2o_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}
		$list=MM("wmo2o","wmo2o_product")->Dselect(array(
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
		$shop=MM("wmo2o","wmo2o_shop")->get($shopid);
		$ratyList=MM("wmo2o","wmo2o_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("wmo2o_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=get("shopid","i");
		$shop=MM("wmo2o","wmo2o_shop")->get($shopid);
		//证件照
		$certList=MM("wmo2o","wmo2o_shop_cert")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>"id ASC",
			"limit"=>20
		));
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
			"certList"=>$certList
		));
		$this->smarty->display("wmo2o_shop/index/index.html");
	}
}