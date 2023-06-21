<?php
use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsSearchRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkWeappQrcodeUrlGenRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsPromotionUrlGenerateRequest;
 
class Config
{
	static public $clientId = "5299841766704a069947c6f869a63aca";
	static public $clientSecret = "3b8e9a2ceabc3068ce72482c33fb86fd9a056f27";
	static public $accessToken ="Your access token";
	static public $refreshToken = "Your refresh token";
	static public $code = "Your code";
}
class taoke_jd_searchControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$word=get("word");
		$catmap=get("catmap","h");
		$this->smarty->goAssign(array(
			"catmap"=>$catmap,
			"word"=>$word
		));
		$this->smarty->display("taoke_pdd_search/index.html");
	}
	
	public function onShow(){
		$id=get("id","h");
		 
		$id=get("id","i");
		$data=M("mod_taoke_searchcache")->selectRow("objectid=".$id." AND k='pdd'");
		if(empty($data)){
			$this->goAll("商品已下架",1);
		}
		$data=str2arr($data["content"]);
		
		//$imgList=$data["small_images"]["string"];
		$this->smarty->goAssign(array(
			"data"=>$data,
			"imgList"=>$imgList
		));
		$this->smarty->display("taoke_pdd_search/show.html");
	}
	
	public function onGetPwd(){
		require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
		$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);
		$id=get("id","i");
		$request = new PddDdkGoodsPromotionUrlGenerateRequest();
		$request->setPId('9313979_116072092');
	 
		$request->setGoodsIdList(array($id));
		$request->setGenerateShortUrl(true);
		$request->setMultiGroup(true);
		$request->setCustomParameters('str');
		$request->setGenerateWeappWebview(true);
		$request->setZsDuoId(1);
		$request->setGenerateWeApp(true);
		$request->setGenerateWeiboappWebview(true);
		$request->setGenerateMallCollectCoupon(true);
		$request->setGenerateSchemaUrl(true);
		$request->setGenerateQqApp(true);
		//$request->setSearchId('str');
		try{
			$response = $client->syncInvoke($request);
		} catch(Com\Pdd\Pop\Sdk\PopHttpException $e){
			//echo $e->getMessage();
			$this->goAll("error",1);
		}
		$content = $response->getContent();
		if(isset($content['error_response'])){
			$this->goAll("error",1);
		}
		$data=$content["goods_promotion_url_generate_response"]["goods_promotion_url_list"][0];
		$this->goAll("success",0,$data);
	
	}
	
	public function onSearchApi(){
		require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";		 
		$client = new PopHttpClient(Config::$clientId, Config::$clientSecret);	
		$request = new PddDdkGoodsSearchRequest();
		$word=get("word","h");
		$request->setKeyword($word);
		$request->setWithCoupon(true);
		//排序
		$orderby=get("orderby","h");
		switch($orderby){
			case "sold_num":
				$request->setSortType(5);
				break;
			case "priceAsc":
				$request->setSortType(3);
				break;
			case "maxBack":
				
				$request->setSortType(14);
				break;
		}
		$startPage=get("per_page","i");
		$startPage++;
		$request->setPage($startPage);
		$request->setPageSize(48);
		/*
		$request->setOptId(1);
		
		
		
		$request->setRangeList('str');
		$request->setCatId(1);
		$request->setGoodsIdList(array(1));
		$request->setMerchantType(1);
		$request->setPid('str');
		$request->setCustomParameters('str');
		$request->setMerchantTypeList(array(1));
		$request->setIsBrandGoods(true);
		$request->setActivityTags(array(1));
		*/
		try{
			$response = $client->syncInvoke($request);
		} catch(Com\Pdd\Pop\Sdk\PopHttpException $e){
			//echo $e->getMessage();
			$this->goAll("error",1);
		}
		$content = $response->getContent();
		if(isset($content['error_response'])){
			$this->goAll("error",1);
		}
		$prolist= $content["goods_search_response"]["goods_list"];
		$list=array();
		if(empty($prolist)){
			$this->goAll("success",0,array(
				"list"=>$list
			));
		}
		
		$indata=array();
		$oids=array();
		foreach($prolist as $k=>$v){
			$list[]=array(
				"title"=>$v["goods_name"],
				"imgurl"=>$v["goods_thumbnail_url"],
				"description"=>$v["goods_desc"],
				"id"=>$v["goods_id"],
				"discount"=>$v["mall_coupon_discount_pct"],
				"juan_money"=>$v["coupon_discount"]/100,
				"coupon_end_time"=>$v["coupon_end_time"],
				"coupon_start_time"=>$v["coupon_start_time"],
				"yj_bl"=>$v["promotion_rate"],//佣金比例
				"yj_money"=>intval($v["min_normal_price"]*$v["promotion_rate"]/1000)/100,
				"sold_num"=>$v["sales_tip"],
				"price"=>$v["min_normal_price"]/100,
				"imgList"=>$v["goods_gallery_urls"]
			);
			$oids[]=$v["goods_id"];
		}
		$objs=M("mod_taoke_searchcache")->selectCols(array(
			"where"=>" objectid in("._implode($oids).") ",
			"fields"=>"objectid"
		));
		$nids=$oids;
		if($objs){
			$nids=array_diff($oids,$objs);
		}
		if(!empty($nids)){
			$indata=array();
			foreach($list as $v){
				if(in_array($v["id"],$nids)){
					$indata[]=array(
						"objectid"=>$v["id"],
						"content"=>arr2str($v),
						"etime"=>date("Y-m-d",$v["coupon_end_time"]),
						"k"=>"pdd",
						"title"=>$v["title"],
						"yj_bl"=>$v["yj_bl"],//佣金比例
						"yj_money"=>$v["yj_money"],
						"sold_num"=>$v["sold_num"],
						"price"=>$v["price"]
					);
				}	
			}
			M("mod_taoke_searchcache")->insertMore($indata);
		}
		$this->goAll("success",0,array(
			"list"=>$list,
			"per_page"=>$startPage,
			"catmap"=>$catmap,
			"word"=>$word
		)); 	
	}
 
}
?>