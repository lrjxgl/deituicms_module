<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class s2c_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=s2c_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$where.=" AND catid=".$catid;
			}else{
				$where.=" AND isrecommend=1 ";
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_s2c_product")->select($option,$rscount);
			//分类
			$catList=MM("s2c","s2c_category")->children(0);
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("s2c","s2c_cart")->getProductAmount("userid=".$userid." AND teamid=".TEAMID." AND ksid=0");
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
			$this->smarty->display("s2c_product/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=s2c_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("s2c","s2c_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			
			
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_s2c_product")->select($option,$rscount);
			//分类
			$cat=MM("s2c","s2c_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title,pid"
			));
			$catList=MM("s2c","s2c_category")->children($catid);
			
			if(empty($catList)){
				$catList=MM("s2c","s2c_category")->children($cat["pid"]);
			}
			
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("s2c","s2c_cart")->getProductAmount("userid=".$userid." AND ksid=0");
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
			$this->smarty->display("s2c_product/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=MM("s2c","s2c_product")->selectRow(array("where"=>"id=".$id));
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["content"]=M("mod_s2c_product_data")->selectOne(array(
				"where"=>"id=".$id,
				"fields"=>"content"
			));
			//款式
			$ksid=0;
			
			$ksList=MM("s2c","s2c_product_ks")->getListByTitle($id);
			if($ksList){
				$ks=$ksList[0];
				$ksid=$ks["id"];
				$ksList2=MM("s2c","s2c_product_ks")->select(array(
					"where"=>" productid=".$ks['productid']." AND title='".$ks['title']."' "
				));
				$data["price"]=$ks["price"];
			}
			$cart_amount=0;
			$userid=M("login")->userid;
			$cart=MM("s2c","s2c_cart")->selectRow("userid=".$userid." AND productid=".$id." AND ksid=".$ksid);
			if($cart){
				$cart_amount=$cart["amount"];
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"ksid"=>$ksid,
				"cart_amount"=>$cart_amount,
				"ksList"=>$ksList,
				"ksList2"=>$ksList2,
			));
			$this->smarty->display("s2c_product/show.html");
		}
		
		public function onRaty(){
			$id=get("id","i");
			$where=" productid=".$id." AND iscomment=1 ";
			$limit=get("limit","i");
			$limit=$limit?$limit:12;
			$start=get("per_page","i");
			$rscount=true;
			$list=M("mod_s2c_order_product")->select(array(
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
			$this->smarty->display("s2c_product/raty.html");
		}
		
	}

?>