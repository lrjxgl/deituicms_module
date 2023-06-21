<?php
class jdo2o_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$shopid=get("shopid","i");
		$shop=MM("jdo2o","jdo2o_shop")->get($shopid);
		$userid=M("login")->userid;
		MM("jdo2o","jdo2o_view_user")->add($shopid,$userid);
		$isfav=0;
		$couponList=null;
		if($userid){
			$fav=M("fav")->selectRow("userid=".$userid." AND tablename='mod_jdo2o_shop' AND objectid=".$shopid);
			if($fav){
				$isfav=1;
			}
			$couponList=MM("jdo2o","jdo2o_coupon")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 "
			));
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"isfav"=>$isfav,
			"couponList"=>$couponList
		));
		if($shop["open_mall"]){
			$this->smarty->display("jdo2o_shop/index/index.html");
		}else{
			$this->onDetail();
		}
		
	}
	
	public function onRecommend(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$where=" shopid=".$shopid." AND status=1  AND isrecommend=1";
		$list=MM("jdo2o","jdo2o_product")->Dselect(array(
			"where"=>$where
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onProduct(){
		$_GET["ajax"]=1;
		$shopid=get("shopid","i");
		$shop=MM("jdo2o","jdo2o_shop")->get($shopid);
		$shop_catlist=MM("jdo2o","jdo2o_shop_product_category")->select(array(
			"where"=>" shopid=".$shopid." AND status=1 AND pid=0 ",
			"order"=>" orderindex asc"
		));
		$where=" shopid=".$shopid." AND status=1";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("jdo2o","jdo2o_shop_product_category")->id_family($catid);
			$where.=" AND shop_catid in("._implode($cids).") ";
		}
		$list=MM("jdo2o","jdo2o_product")->Dselect(array(
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
		$shop=MM("jdo2o","jdo2o_shop")->get($shopid);
		$ratyList=MM("jdo2o","jdo2o_order_raty")->Dselect(array(
			"where"=>"shopid=".$shopid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"ratyList"=>$ratyList
		));
		$this->smarty->display("jdo2o_shop/index/index.html");
	}
	public function onDetail(){
		$shopid=get("shopid","i");
		if(!$shopid){
			$shopid=SHOPID;
		}
		$shop=MM("jdo2o","jdo2o_shop")->get($shopid,"*");
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
		$this->smarty->display("jdo2o_shop/index/detail.html");
	}
	
	public function onGetShop(){
		$shopid=get("shopid","i");
		$shop=MM("jdo2o","jdo2o_shop")->get($shopid,"shopid,shopname,followed_num,hot_num,blog_num,place_num,imgurl");
		$userid=M("login")->userid;
		$row=M("mod_jdo2o_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($row){
			$isFollow=1;
		}else{
			$isFollow=0;
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"shop"=>$shop,
				"isFollow"=>$isFollow
			)
		));
	}
	
	public function onToggleFollow(){
		$shopid=get("shopid","i");
		$userid=M("login")->userid;
		$row=M("mod_jdo2o_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($row){
			M("mod_jdo2o_follow")->delete("id=".$row["id"]);
			M("mod_jdo2o_shop")->changenum("followed_num",-1,"shopid=".$shopid);
			$action="delete";
		}else{
			M("mod_jdo2o_follow")->insert(array(
				"shopid"=>$shopid,
				"userid"=>$userid,
				"dateline"=>time()
			));
			M("mod_jdo2o_shop")->changenum("followed_num",1,"shopid=".$shopid);
			$action="add";
		}
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>$action
		));
	}
	
}