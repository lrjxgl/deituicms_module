<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class household_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=household_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("household","household_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}else{
				$where.=" AND isrecommend=1 ";
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
				"order"=>$orderby,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_household_product")->select($option,$rscount);
			//分类
			$catList=MM("household","household_category")->children(0);
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("household","household_cart")->getProductAmount("userid=".$userid." AND ksid=0");
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
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"catList"=>$catList,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("household_product/index.html");
			
			
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=household_product&a=list";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("household","household_category")->id_family($catid);
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
				"order"=>$orderby,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_household_product")->select($option,$rscount);
			//分类
			$cat=MM("household","household_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title,pid"
			));
			$catList=MM("household","household_category")->children($catid);
			
			 
			
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("household","household_cart")->getProductAmount("userid=".$userid." AND ksid=0");
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
			$this->smarty->display("household_product/list.html");
		}
		
		
		
		public function onShow(){
			M("login")->setBackurl();
			$id=get_post("id","i");
			$userid=M("login")->userid;
			$data=MM("household","household_product")->selectRow(array("where"=>"id=".$id));
			if(empty($data)){
				$this->goAll("商品已下架",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["videourl"]=images_site($data["videourl"]);
			$data+=M("mod_household_product_data")->selectRow(array(
				"where"=>"id=".$id,
				"fields"=>"content,fysm"
			));
			$imsdata=array();
			if($data["imgsdata"]){
				$ims=explode(",",$data["imgsdata"]);
				
				foreach($ims as $im){
					$imgsdata[]=images_site($im);
				}
				
			}
			 
			//款式
			$ksid=0;
			
			$ksList=MM("household","household_product_ks")->getListByTitle($id);
			if($ksList){
				$ks=$ksList[0];
				$ksid=$ks["id"];
				$ksList2=MM("household","household_product_ks")->select(array(
					"where"=>" productid=".$ks['productid']." AND title='".$ks['title']."' "
				));
				$data["price"]=$ks["price"];
			}
			$cart_amount=0;
			
			$cart=MM("household","household_cart")->selectRow("userid=".$userid." AND productid=".$id." AND ksid=".$ksid);
			if($cart){
				$cart_amount=$cart["amount"];
			}
			$url=HTTP_HOST."/module.php?m=household_product&a=show&id=".$id;
			$sharePic=HTTP_HOST."/index.php?m=gd&a=ShareProduct&imgurl=".$data["imgurl"]."&title=".urlencode($data["title"])."&price=".$data["price"]."&url=".urlencode($url);
			//是否收藏
			$isfav=0;
			if($userid){
				$fav=M("fav")->selectRow("tablename='mod_household_product' AND userid=".$userid." AND objectid=".$id);
				if($fav){
					$isfav=1;
				}
			}
			//扩展表单
			 $cat=M("mod_household_category")->selectRow("catid=".$data["catid"]);
			 if($data["ex_table_data_id"]){
				 $fieldsList=M("table_data")->get($cat["ex_table_id"],$data["ex_table_data_id"]);
			 }
			//相关推荐
			$cids=MM("household","household_category")->id_family($data["catid"]);
			if($cids){
				$where=" status=1 AND catid in("._implode($cids).") ";
			}else{
				$where=" status=1  ";
			}
			$recList=MM("household","household_product")->Dselect(array(
				"where"=>$where,
				"limit"=>12,
				"order"=>"buy_num DESC"
			 ));
			//统计访问数
			$vk="householdProVC".session_id().$id;
			if(!cache()->get($vk)){
				cache()->set($vk,1,3600*24);
				M("mod_household_product")->changenum("view_num",1,"id=".$id); 
			}
			$tpl=M("pagetpl")->get("household_product","show");
			$pts=false;
			$pts_num=0;
			$order=false;
			if($data["pt_open"]){
				//拼团订单相关
				$orderid=get_post("orderid","i");
				if($orderid){
					$order=M("mod_household_order")->selectRow(array(
						"where"=>"orderid=".$orderid." AND ispay=1 AND pin_orderid=0 "
					));
					if($order){
						$order["nickname"]=M("user")->selectOne(array(
							"where"=>" userid=".$order["userid"],
							"fields"=>"nickname"
						));
						$order["timego"]=strtotime($order["createtime"])+24*3600-time();
						$order["timestr"]=timeago($order["timego"]);
					}
					
				}
				
				//拼团
				$ctime=date("Y-m-d H:i:s",time()-3600*24);
				$pts=M("mod_household_order")->select(array(
					"where"=>" productid=".$id." AND ispin=1 AND ispay=1 AND pin_orderid=0  AND status=0 AND pin_success=0 AND createtime>'".$ctime."'",
					"fields"=>" orderid,productid,userid,pin_num,createtime ",
					"limit"=>6
				));
				$pts_num=M("mod_household_order")->selectOne(array(
					"where"=>" productid=".$id." AND ispin=1 AND ispay=1 AND pin_orderid=0  AND status=0 ",
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
						$v["timestr"]=timeago($v["timego"]);
						$pts[$k]=$v;
						
					}
				}
				
				$tpl="household_product/show_pintuan.html";
			}elseif($data["otype"]=='isflash'){
				$tpl="household_product/show_flash.html";
			}
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$this->smarty->goassign(array(
				"data"=>$data,
				"seo"=>$seo,
				"ksid"=>$ksid,
				"sharePic"=>$sharePic,
				"cart_amount"=>$cart_amount,
				"ksList"=>$ksList,
				"ksList2"=>$ksList2,
				"fieldsList"=> $fieldsList,
				"pts"=>$pts,
				"pts_num"=>$pts_num,
				"isfav"=>$isfav,
				"order"=>$order,
				"imgsdata"=>$imgsdata,
				"recList"=>$recList
			));
			
			
			
			$this->smarty->display($tpl);
		}
		
		public function onRaty(){
			$id=get("id","i");
			$where=" productid=".$id." AND iscomment=1 ";
			$limit=get("limit","i");
			$limit=$limit?$limit:12;
			$start=get("per_page","i");
			$rscount=true;
			$list=M("mod_household_order_product")->select(array(
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
			$this->smarty->display("household_product/raty.html");
		}
		
	}

?>