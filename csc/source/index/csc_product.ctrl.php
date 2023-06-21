<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$shopid=SHOPID; 
			$where=" shopid=".SHOPID." AND  status in(0,1,2)";
			$url="/module.php?m=csc_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			$recWhere=" isrecommend=1 AND status=1 ";
			if($catid){
				$cids=MM("csc","csc_shop_product_category")->id_family($catid);
				$where.=" AND shop_catid in("._implode($cids).") ";
				$recWhere.=" AND shop_catid in("._implode($cids).") ";
			}else{
				$where.=" AND isrecommend=1 ";
			}
			$order=" id DESC";
			$type=get("type");
			switch($type){
				case "new":
					$where.=" AND isnew=1 ";
					break;
				case "sold":
					$order=" month_buy_num DESC ";
					break;
				case "price":
					$order=" pt_price ASC";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			 
			$rscount=true;
			$data=M("mod_csc_product")->select($option,$rscount);
			//分类
			$catList=MM("csc","csc_shop_product_category")->children($shopid);
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("csc","csc_cart")->getProductAmount("userid=".$userid." AND ksid=0");
			$cartList=MM("csc","csc_cart")->Dselect(array(
				"where"=>" shopid=".$shopid
			));
			$cart_total_num=0;
			$cart_total_money=0;
			$weight=0;
			if($cartList){
				foreach($cartList as $v){
					$weight+=$v["amount"]*$v["weight"];
					$cart_total_num+=$v["amount"];
					$cart_total_money+=$v["amount"]*$v["price"];
				}
			}
			$user_address_id=M("user_address")->selectOne(array(
				"where"=>" userid=".$userid,
				"fields"=>"id",
				"order"=>" isdefault DESC,id DESC"
			));
			$express_money=M("express_fee")->getMoney($user_address_id,$weight);
			if($data){
				foreach($data as $k=>$v){
					$v["incart"]=0;
					$v["cart_amount"]=0;
					$v["imgurl"]=images_site($v["imgurl"]);
					if($cartPros && isset($cartPros[$v["id"]])){
						$v["incart"]=1;
						$v["cart_amount"]=$cartPros[$v['id']];
						$cart_num+=$v["cart_amount"];
					} 
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			//推荐商品
			$recList=MM("csc","csc_product")->Dselect(array(
				"where"=>" isrecommend=1 AND status=1 ",
				"order"=>"buy_num DESC",
				"limit"=>12
			));
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"catList"=>$catList,
					"rscount"=>$rscount,
					"url"=>$url,
					"cart_total_num"=>$cart_total_num,
					"cart_total_money"=>$cart_total_money,
					"cart_amount"=>$cart_amount,
					"express_money"=>$express_money,
					"recList"=>$recList
				)
			);
			$this->smarty->display("csc_product/index.html");
		}
		 
		public function onList(){
			$where=" shopid=".SHOPID." AND status in(0,1,2)";
			$url="/module.php?m=csc_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("csc","csc_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			$order=" id DESC";
			$iPlan=get("iPlan","i");
			if(!$iPlan){
				$where.=" AND isplan=0 ";
			}
			$iPrice=get("iPrice","i");
			switch($iPrice){
				case 1:
					$order="price ASC";
					break;
				case 2:
					$order="price DESC";
			}
			 
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_product")->select($option,$rscount);
			//分类
			$cat=MM("csc","csc_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title,pid"
			));
			//上级分类
			$pcatid=$cat["pid"]?$cat["pid"]:$catid;
			$pcat=MM("csc","csc_category")->selectRow(array(
				"where"=>"catid=".$pcatid,
				"fields"=>"catid,title,pid"
			));
			$catList=MM("csc","csc_category")->children($pcatid);
			
			 
			
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("csc","csc_cart")->getProductAmount("userid=".$userid." AND ksid=0");
			if($data){
				foreach($data as $k=>$v){
					$v["incart"]=0;
					$v["cart_amount"]=0;
					$v["imgurl"]=images_site($v["imgurl"]);
					if($cartPros && isset($cartPros[$v["id"]])){
						$v["incart"]=1;
						$v["cart_amount"]=$cartPros[$v['id']];
					} 
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			
			$this->smarty->goassign(
				array(
					"pcat"=>$pcat,
					"cat"=>$cat,
					"catList"=>$catList,
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("csc_product/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=MM("csc","csc_product")->selectRow(array("where"=>"id=".$id));
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["content"]=M("mod_csc_product_data")->selectOne(array(
				"where"=>"id=".$id,
				"fields"=>"content"
			));
			//款式
			$ksid=0;
			
			$ksList=MM("csc","csc_product_ks")->getListByTitle($id);
			if($ksList){
				$ks=$ksList[0];
				$ksid=$ks["id"];
				$ksList2=MM("csc","csc_product_ks")->select(array(
					"where"=>" productid=".$ks['productid']." AND title='".$ks['title']."' "
				));
				$data["price"]=$ks["price"];
			}
			$cart_amount=0;
			$userid=M("login")->userid;
			$cart=MM("csc","csc_cart")->selectRow("userid=".$userid." AND productid=".$id." AND ksid=".$ksid);
			if($cart){
				$cart_amount=$cart["amount"];
			}
			$shopid=$data["shopid"];
			$shop=MM("csc","csc_shop")->get($shopid,"shopid,shopname,imgurl,raty_grade,buy_num");
			//是否收藏
			$isfav=0;
			if($userid){
				$fav=M("fav")->selectRow("tablename='mod_csc_product' AND userid=".$userid." AND objectid=".$id);
				if($fav){
					$isfav=1;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"ksid"=>$ksid,
				"cart_amount"=>$cart_amount,
				"ksList"=>$ksList,
				"ksList2"=>$ksList2,
				"shop"=>$shop,
				"isfav"=>$isfav,
			));
			$this->smarty->display("csc_product/show.html");
		}
		
		public function onRaty(){
			$id=get("id","i");
			$where=" productid=".$id." AND iscomment=1 ";
			$limit=get("limit","i");
			$limit=$limit?$limit:12;
			$start=get("per_page","i");
			$rscount=true;
			$list=M("mod_csc_order_product")->select(array(
				"where"=>$where,
				"limit"=>$limit,
				"start"=>$start,
				"order"=>" id DESC"
			),$rscount);
			if($list){
				foreach($list as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids,"userid,user_head,nickname");
				foreach($list as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["user_head"]=$us[$v["userid"]]["user_head"];
					$list[$k]=$v;
				}
			}
			
			$this->smarty->goAssign(array(
				"productid"=>$id,
				"list"=>$list,
				"rscount"=>$rscount
			));
			$this->smarty->display("csc_product/raty.html");
		}
		
	}

?>