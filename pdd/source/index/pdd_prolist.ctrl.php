<?php
class pdd_prolistControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status in(0,1,2)";
		$url="/module.php?m=pdd_product&a=default";
		$limit=20;
		$start=get("per_page","i");
		$catid=get("catid","i");
		$cat=[];
		if($catid){
			$cat=MM("pdd","pdd_category")->selectRow("catid=".$catid);
			$cids=MM("pdd","pdd_category")->id_family($catid);
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
		$data=M("mod_pdd_product")->select($option,$rscount);
		//分类
		if($cat && $cat["pid"]){
			$catList=MM("pdd","pdd_category")->children($cat["pid"]);
		}else{
			$catList=MM("pdd","pdd_category")->children($catid);
		}
		
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
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"catList"=>$catList,
				"rscount"=>$rscount,
				"url"=>$url,
				"cat"=>$cat
			)
		);
		$this->smarty->display("pdd_prolist/index.html");
		
	}
	
}