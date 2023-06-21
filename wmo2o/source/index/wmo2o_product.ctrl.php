<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class wmo2o_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$shopid=get("shopid","i");
			$where=" shopid=".$shopid." AND  status in(0,1,2)";
			$url="/module.php?m=wmo2o_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			$recWhere=" isrecommend=1 AND status=1 ";
			if($catid){
				$cids=MM("wmo2o","wmo2o_shop_product_category")->id_family($catid);
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
			$orderby=get("orderby","h");
			switch($orderby){
				case "price":
					$order="price ASC,id DESC";
					break;
				case "buy_num":
					$order="buy_num DESC";
					break;
				default:
					$order=" id DESC";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			 
			$rscount=true;
			$data=M("mod_wmo2o_product")->select($option,$rscount);
			//分类
			$catList=MM("wmo2o","wmo2o_shop_product_category")->children($shopid);
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("wmo2o","wmo2o_cart")->getProductAmount("userid=".$userid." AND shopid=".$shopid." AND ksid=0");
			
			$cartList=MM("wmo2o","wmo2o_cart")->Dselect(array(
				"where"=>" shopid=".$shopid." AND userid=".$userid
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
			$express_money=MM("wmo2o","wmo2o_express_fee")->getMoney($user_address_id,$weight,$shopid,$shop["express_money"]);
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
			$recList=MM("wmo2o","wmo2o_product")->Dselect(array(
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
			$this->smarty->display("wmo2o_product/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=wmo2o_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("wmo2o","wmo2o_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			
			$idstr=get("ids");
			if(!empty($idstr)){
				$idss=explode(",",$idstr);
				$ids=array();
				foreach($idss as $v){
					if(intval($v)>0){
						$ids[]=intval($v);
					}
				} 
				$where.=" AND id in("._implode($ids).") ";
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
			$orderby=get("orderby","h");
			switch($orderby){
				case "price":
					$order="price ASC,id DESC";
					break;
				case "buy_num":
					$order="buy_num DESC";
					break;
				default:
					$order=" id DESC";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$orderbyorder,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_wmo2o_product")->select($option,$rscount);
			//分类
			$cat=MM("wmo2o","wmo2o_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title,pid"
			));
			$catList=MM("wmo2o","wmo2o_category")->children($catid);
			
			 
			
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("wmo2o","wmo2o_cart")->getProductAmount("userid=".$userid." AND ksid=0");
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
					"cat"=>$cat,
					"catList"=>$catList,
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("wmo2o_product/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=MM("wmo2o","wmo2o_product")->selectRow(array("where"=>"id=".$id));
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["content"]=M("mod_wmo2o_product_data")->selectOne(array(
				"where"=>"id=".$id,
				"fields"=>"content"
			));
			//款式
			$ksid=0;
			
			$ksList=MM("wmo2o","wmo2o_product_ks")->getListByTitle($id);
			if($ksList){
				$ks=$ksList[0];
				$ksid=$ks["id"];
				$ksList2=MM("wmo2o","wmo2o_product_ks")->select(array(
					"where"=>" productid=".$ks['productid']." AND title='".$ks['title']."' "
				));
				$data["price"]=$ks["price"];
			}
			$cart_amount=0;
			$userid=M("login")->userid;
			$cart=MM("wmo2o","wmo2o_cart")->selectRow("userid=".$userid." AND productid=".$id." AND ksid=".$ksid);
			if($cart){
				$cart_amount=$cart["amount"];
			}
			$shopid=$data["shopid"];
			$shop=MM("wmo2o","wmo2o_shop")->get($shopid,"shopid,shopname,imgurl,raty_grade,buy_num");
			$this->smarty->goassign(array(
				"data"=>$data,
				"ksid"=>$ksid,
				"cart_amount"=>$cart_amount,
				"ksList"=>$ksList,
				"ksList2"=>$ksList2,
				"shop"=>$shop,
			));
			$this->smarty->display("wmo2o_product/show.html");
		}
		
		public function onDetailRecList(){
			$id=get_post("id","i");
			$data=MM("wmo2o","wmo2o_product")->selectRow(array("where"=>"id=".$id));
			$recList=MM("wmo2o","wmo2o_product")->Dselect(array(
				"where"=>" status=1 AND shopid=".$data["shopid"]." ",
				"order"=>" month_buy_num DESC",
				"limit"=>12,
			));
			$this->smarty->goAssign(array(
				"recList"=>$recList
			));
		}
		
	}

?>