<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pdd_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$shopid=get("shopid","i");
			$where=" shopid=".$shopid." AND  status in(0,1,2)";
			$url="/module.php?m=pdd_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("pdd","pdd_shop_product_category")->id_family($catid);
				$where.=" AND shop_catid in("._implode($cids).") ";
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
			$data=M("mod_pdd_product")->select($option,$rscount);
			//分类
			$catList=MM("pdd","pdd_shop_product_category")->children($shopid);
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("pdd","pdd_cart")->getProductAmount("userid=".$userid." AND ksid=0");
			$cartList=MM("pdd","pdd_cart")->Dselect(array(
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
					"express_money"=>$express_money
				)
			);
			$this->smarty->display("pdd_product/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=pdd_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("pdd","pdd_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			
			
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pdd_product")->select($option,$rscount);
			//分类
			$cat=MM("pdd","pdd_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title,pid"
			));
			$catList=MM("pdd","pdd_category")->children($catid);
			
			 
			
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("pdd","pdd_cart")->getProductAmount("userid=".$userid." AND ksid=0");
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
			$this->smarty->display("pdd_product/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=MM("pdd","pdd_product")->selectRow(array("where"=>"id=".$id));
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["content"]=M("mod_pdd_product_data")->selectOne(array(
				"where"=>"id=".$id,
				"fields"=>"content"
			));
			//款式
			$ksid=0;
			
			$ksList=MM("pdd","pdd_product_ks")->getListByTitle($id);
			if($ksList){
				$ks=$ksList[0];
				$ksid=$ks["id"];
				$ksList2=MM("pdd","pdd_product_ks")->select(array(
					"where"=>" productid=".$ks['productid']." AND title='".$ks['title']."' "
				));
				$data["price"]=$ks["price"];
			}
			$cart_amount=0;
			$userid=M("login")->userid;
			$cart=MM("pdd","pdd_cart")->selectRow("userid=".$userid." AND productid=".$id." AND ksid=".$ksid);
			if($cart){
				$cart_amount=$cart["amount"];
			}
			$shopid=$data["shopid"];
			$shop=MM("pdd","pdd_shop")->get($shopid,"shopid,shopname,buy_num,product_num,imgurl,raty_grade");
			
			$url=HTTP_HOST."/module.php?m=pdd_product&a=show&id=".$id;
			$sharePic=HTTP_HOST."/index.php?m=gd&a=ShareProduct&imgurl=".$data["imgurl"]."&title=".urlencode($data["title"])."&price=".$data["price"]."&url=".urlencode($url);
			//是否收藏
			$isfav=0;
			if($userid){
				$fav=M("fav")->selectRow("tablename='mod_pdd_product' AND userid=".$userid." AND objectid=".$id);
				if($fav){
					$isfav=1;
				}
			}
			//扩展表单
			 $cat=M("mod_pdd_category")->selectRow("catid=".$data["catid"]);
			 if($data["ex_table_data_id"]){
				 $fieldsList=M("table_data")->get($cat["ex_table_id"],$data["ex_table_data_id"]);
			 }
			//统计访问数
			$vk="pddProVC".session_id().$id;
			if(!cache()->get($vk)){
				cache()->set($vk,1,3600*24);
				M("mod_pdd_product")->changenum("view_num",1,"id=".$id); 
			}
			$tpl=M("pagetpl")->get("pdd_product","show");
			$pts=false;
			$pts_num=0;
			$order=false;
			if($data["pt_open"]){
				//拼团订单相关
				$orderid=get_post("orderid","i");
				if($orderid){
					$order=M("mod_pdd_order")->selectRow(array(
						"where"=>"orderid=".$orderid
					));
					$order["nickname"]=M("user")->selectOne(array(
						"where"=>" userid=".$order["userid"],
						"fields"=>"nickname"
					));
					$order["timego"]=strtotime($order["createtime"])+24*3600-time();
				}
				
				//拼团
				$ctime=date("Y-m-d H:i:s",time()-3600*24);
				$pts=M("mod_pdd_order")->select(array(
					"where"=>" productid=".$id." AND ispay=1 AND pin_orderid=0 AND ispin=1 AND status=0 AND pin_success=0 AND createtime>'".$ctime."'",
					"fields"=>" orderid,productid,userid,pin_num,createtime ",
					"limit"=>6
				));
				 
				$pts_num=M("mod_pdd_order")->selectOne(array(
					"where"=>" productid=".$id." AND ispay=1 AND pin_orderid=0 AND ispin=1 AND status=0 ",
					"fields"=>" count(*)"
				));
				if($pts){
					foreach($pts as $v){
						$uids[]=$v["userid"];
					}
					$us=M("user")->getUserByIds($uids,"userid,user_head,nickname");
					foreach($pts as $k=>$v){
						$v["nickname"]=$us[$v["userid"]]["nickname"];
						$v["user_head"]=$us[$v["userid"]]["user_head"];
						$v["need_num"]=$data["pt_min"]-$v["pin_num"];
						$v["timego"]=strtotime($v["createtime"])+24*3600-time();
						$pts[$k]=$v;
						
					}
				}
				
				$tpl="pdd_product/show_pintuan.html";
			}
		 
			$this->smarty->goassign(array(
				"data"=>$data,
				"ksid"=>$ksid,
				"cart_amount"=>$cart_amount,
				"ksList"=>$ksList,
				"ksList2"=>$ksList2,
				"shop"=>$shop,
				"fieldsList"=> $fieldsList,
				"pts"=>$pts,
				"pts_num"=>$pts_num,
				"isfav"=>$isfav,
				"order"=>$order
			));
			$this->smarty->display("pdd_product/show.html");
		}
		
	}

?>