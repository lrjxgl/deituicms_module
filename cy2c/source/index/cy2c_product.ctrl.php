<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cy2c_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=cy2c_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("cy2c","cy2c_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
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
			$data=M("mod_cy2c_product")->select($option,$rscount);
			//分类
			$catList=MM("cy2c","cy2c_category")->children(0);
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("cy2c","cy2c_cart")->getProductAmount(" ksid=0 AND placeid=".PLACEID);
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
			 
			$place=M("mod_cy2c_place")->selectRow("placeid=".PLACEID);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"catList"=>$catList,
					"place"=>$place,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			if(MCY2C_SHOPTYPE=='diancan'){
				$this->smarty->display("cy2c_product/index.html");
			}else{
				$this->smarty->display("cy2c_product/index_product.html");
			}
			
			
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=cy2c_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$cids=MM("cy2c","cy2c_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
			}
			
			
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cy2c_product")->select($option,$rscount);
			//分类
			$cat=MM("cy2c","cy2c_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title,pid"
			));
			$catList=MM("cy2c","cy2c_category")->children($catid);
			
			 
			
			//判断产品是否在购物车
			$userid=M("login")->userid;
			$cartPros=MM("cy2c","cy2c_cart")->getProductAmount("userid=".$userid." AND ksid=0");
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
			$this->smarty->display("cy2c_product/list.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=MM("cy2c","cy2c_product")->selectRow(array("where"=>"id=".$id));
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["content"]=M("mod_cy2c_product_data")->selectOne(array(
				"where"=>"id=".$id,
				"fields"=>"content"
			));
			//款式
			$ksid=0;
			
			$ksList=MM("cy2c","cy2c_product_ks")->getListByTitle($id);
			if($ksList){
				$ks=$ksList[0];
				$ksid=$ks["id"];
				$ksList2=MM("cy2c","cy2c_product_ks")->select(array(
					"where"=>" productid=".$ks['productid']." AND title='".$ks['title']."' "
				));
				$data["price"]=$ks["price"];
			}
			$cart_amount=0;
			$userid=M("login")->userid;
			$cart=MM("cy2c","cy2c_cart")->selectRow("userid=".$userid." AND productid=".$id." AND ksid=".$ksid);
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
			$this->smarty->display("cy2c_product/show.html");
		}
		
	}

?>