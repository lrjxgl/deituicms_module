<?php
use Com\Pdd\Pop\Sdk\PopHttpClient;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsSearchRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkWeappQrcodeUrlGenRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkGoodsPromotionUrlGenerateRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddDdkOrderDetailGetRequest;
use Com\Pdd\Pop\Sdk\Api\Request\PddGoodsCatsGetRequest;

class taoke_pdd_searchControl extends skymvc
{
    public function __construct()
    {
        parent::__construct();
    }
    public function onDefault()
    {
        $word=get("word");
        $catmap=get("catmap", "h");
        $this->smarty->goAssign(array(
            "catmap"=>$catmap,
            "word"=>$word
        ));
        $this->smarty->display("taoke_pdd_search/index.html");
    }
    
    public function onShow()
    {
        $id=get("id", "h");
         
        $id=get("id", "i");
        $data=M("mod_taoke_searchcache")->selectRow("objectid=".$id." AND k='pdd'");
        if (empty($data)) {
            $this->goAll("商品已下架", 1);
        }
        $userid=M("login")->userid;
        $love_num=$data["love_num"];
        $data=str2arr($data["content"]);
        $data["love_num"]=$love_num;
        M("mod_taoke_searchcache")->changenum("view_num", 1, "objectid=".$id." AND k='pdd'");
        $islove=0;
        $fav=M("mod_taoke_love")->selectRow("productid=".$id." AND userid=".$userid." AND k='pdd' ");
        if ($fav) {
            $islove=1;
        }
        $this->smarty->goAssign(array(
            "data"=>$data,
            "imgList"=>$imgList,
            "islove"=>$islove,
            "sharePic"=>HTTP_HOST."/module.php?m=taoke_pdd_search&a=sharepic&nologin=1&id=".$id."&invite_uid=".$userid,
			"shareUrl"=>HTTP_HOST."/module.php?m=taoke_pdd_search&a=show&id=".$id."&invite_uid=".$userid
        ));
        $this->smarty->display("taoke_pdd_search/show.html");
    }
    public function onWxCode()
    {
        require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
        $config=M("mod_taoke_pdd_config")->selectRow();
        $client = new PopHttpClient($config["appkey"], $config["secretKey"]);
        $id=get("id", "i");
        $request = new PddDdkWeappQrcodeUrlGenRequest();
        $request->setPId($config["pid"]);
        $request->setGoodsIdList(array($id));
        $request->setCustomParameters('str');
        $request->setZsDuoId(1);
        $request->setGenerateMallCollectCoupon(true);
        try {
            $response = $client->syncInvoke($request);
        } catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        if (isset($content['error_response'])) {
            echo "异常返回";
        }
        echo json_encode($content, JSON_UNESCAPED_UNICODE);
    }
    public function onGetPwd()
    {
        require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
         
        $id=get("id", "i");
		$data=M("mod_taoke_searchcache")->selectRow(array(
		    "where"=>"objectid=".$id
		));
        $config=M("mod_taoke_pdd_config")->selectRow();
        $client = new PopHttpClient($config["appkey"], $config["secretKey"]);
        $request = new PddDdkGoodsPromotionUrlGenerateRequest();
        /*
		$request->setCashGiftId(0L);
        $request->setCashGiftName('str');
		*/
	   /*
	    $userid=M("login")->userid;
        $cus=array(
            "userid"=>$userid
        );
        $request->setCustomParameters(json_encode($cus));
		*/
		$request->setCustomParameters($config["custom_parameters"]);
        $request->setGenerateAuthorityUrl(false);
        $request->setGenerateMallCollectCoupon(false);
        $request->setGenerateQqApp(true);
        $request->setGenerateSchemaUrl(true);
        $request->setGenerateShortUrl(true);
        $request->setGenerateWeApp(true);
        $goodsSignList = array();
        $goodsSignList[] = get("goods_sign","h");
        $request->setGoodsSignList($goodsSignList);
        $request->setMultiGroup(false);
        $request->setPId($config["pid"]);
       // $request->setSearchId('str');
        //$request->setZsDuoId($config[""]);
        //$request->setSearchId('str');
        try {
            $response = $client->syncInvoke($request);
        } catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
            //echo $e->getMessage();
            $this->goAll("error", 1);
        }
        $content = $response->getContent();
		//print_r($content);
        if (isset($content['error_response'])) {
            $this->goAll("error", 1);
        }
 
        $data=$content["goods_promotion_url_generate_response"]["goods_promotion_url_list"][0];
		
        $this->goAll("success", 0, $data);
    }
    
    public function onSearchApi()
    {
        require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
        $config=M("mod_taoke_pdd_config")->selectRow();
        $client = new PopHttpClient($config["appkey"], $config["secretKey"]);
        $request = new PddDdkGoodsSearchRequest();
        $word=get("word", "h");
        $request->setKeyword($word);
        $request->setWithCoupon(true);
		$request->setCustomParameters($config["custom_parameters"]);
		$request->setPId($config["pid"]);
        //排序
        $orderby=get("orderby", "h");
        switch ($orderby) {
            case "sold_num":
                $request->setSortType(6);
                break;
            case "priceAsc":
                $request->setSortType(3);
                break;
            case "maxBack":
                
                $request->setSortType(14);
                break;
        }
        $startPage=get("per_page", "i");
        $startPage++;
        $request->setPage($startPage);
		$limit=12;
        $request->setPageSize($limit);
        $catid=get("catid", "i");
        if ($catid) {
            $request->setCatId($catid);
        }
        $type=get("type", "h");
        switch ($type) {
            case "gaofan":
                $rangList='[
					{"range_id":2,"range_from":500,"range_to":55500},
					{"range_id":6,"range_from":300,"range_to":150000},
					{"range_id":5,"range_from":200,"range_to":1234566}
				]';
                $request->setRangeList($rangList);
                $request->setIsBrandGoods(true);
                break;
            default:
            /**
             * 0，最小成团价 
			 1，券后价 
			 2，佣金比例 
			 3，优惠券价格 
			 4，广告创建时间 
			 5，销量 
			 6，佣金金额 
			 7，店铺描述分 
			 8，店铺物流分 
			 9，店铺服务分 
			 10， 店铺描述分击败同行业百分比 
			 11， 店铺物流分击败同行业百分比 
			 12，店铺服务分击败同行业百分比 
			 13，商品分 
			 17 ，优惠券/最小团购价 
			 18，过去两小时pv 19，过去两小时销量
             */
                $rangList='[
					{"range_id":2,"range_from":50,"range_to":500},
					{"range_id":6,"range_from":100,"range_to":150000},
					{"range_id":5,"range_from":50,"range_to":1234566}
				]';
                $request->setRangeList($rangList);
                break;
        }
        /*
        $request->setOptId(1);



        $request->setRangeList('str');


        $request->setGoodsIdList(array(1));
        $request->setMerchantType(1);
        $request->setPid('str');
        $request->setCustomParameters('str');
        $request->setMerchantTypeList(array(1));

        $request->setActivityTags(array(1));
        */
        try {
            $response = $client->syncInvoke($request);
        } catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
            //echo $e->getMessage();
            $this->goAll("error", 1);
        }
        $content = $response->getContent();
        if (isset($content['error_response'])) {
            $this->goAll("error", 1);
        }
        $prolist= $content["goods_search_response"]["goods_list"];
        $list=array();
        if (empty($prolist)) {
            $this->goAll("success", 0, array(
                "list"=>$list
            ));
        }
        
        $indata=array();
        $oids=array();
        foreach ($prolist as $k=>$v) {
            $snum=$v["sales_tip"];
            if (preg_match("/万/", $snum)) {
                $snum=intval($snum)*10000;
            }
            $list[]=array(
                "title"=>$v["goods_name"],
                "imgurl"=>$v["goods_thumbnail_url"],
                "description"=>$v["goods_desc"],
                "id"=>$v["goods_id"],
                "discount"=>$v["mall_coupon_discount_pct"],
                "juan_money"=>$v["coupon_discount"]/100,
                "coupon_end_time"=>$v["coupon_end_time"],
                "coupon_start_time"=>$v["coupon_start_time"],
                "yj_bl"=>$v["promotion_rate"]*10,//佣金比例
                "yj_money"=>intval($v["min_normal_price"]*$v["promotion_rate"]/1000)/100,
                "sold_num"=>$snum,
                "price"=>$v["min_normal_price"]/100,
                "imgList"=>$v["goods_gallery_urls"],
				"goods_sign"=>$v["goods_sign"]
            );
            $oids[]=$v["goods_id"];
        }
        $objs=M("mod_taoke_searchcache")->selectCols(array(
            "where"=>" objectid in("._implode($oids).") ",
            "fields"=>"objectid"
        ));
        $nids=$oids;
        if ($objs) {
            $nids=array_diff($oids, $objs);
        }
        if (!empty($nids)) {
            $indata=array();
            foreach ($list as $v) {
                if (in_array($v["id"], $nids)) {
                    $indata[]=array(
                        "objectid"=>$v["id"],
                        "content"=>arr2str($v),
                        "etime"=>date("Y-m-d", $v["coupon_end_time"]),
                        "k"=>"pdd",
                        "title"=>$v["title"],
                        "yj_bl"=>$v["yj_bl"],//佣金比例
                        "yj_money"=>$v["yj_money"],
                        "sold_num"=>$v["sold_num"],
                        "price"=>$v["price"],
                        "juan_money"=>$v["juan_money"],
						"imgurl"=>$v["imgurl"],
						"goods_sign"=>$v["goods_sign"],
						"createtime"=>time()
                    );
                }
            }
            M("mod_taoke_searchcache")->insertMore($indata);
        }
		if(count($list)<$limit){
			$startPage=0;
		}
        $this->goAll("success", 0, array(
            "list"=>$list,
            "per_page"=>$startPage,
            "catmap"=>$catmap,
            "word"=>$word
        ));
    }
    
    public function onGetOrder()
    {
        require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
        $config=M("mod_taoke_pdd_config")->selectRow();
        $client = new PopHttpClient($config["appkey"], $config["secretKey"]);
        $orderno=get("orderno");
        $request = new PddDdkOrderDetailGetRequest();
        $request->setOrderSn($orderno);
        try {
            $response = $client->syncInvoke($request);
        } catch (Com\Pdd\Pop\Sdk\PopHttpException $e) {
            //echo $e->getMessage();
            echo json_encode(array(
                "error"=>1
            ));
            exit;
        }
        $content = $response->getContent();
        if (isset($content['error_response'])) {
            echo json_encode(array(
                "error"=>1
            ));
            exit;
        }
        
        echo json_encode(array(
            "error"=>0,
            "data"=>$content["order_detail_response"]
        ), JSON_UNESCAPED_UNICODE);
        //echo json_encode($content,JSON_UNESCAPED_UNICODE);
    }
    public function onCategory()
    {
        /*
        require_once ROOT_PATH."module/taoke/ddksdk/vendor/autoload.php";
        $config=M("mod_taoke_pdd_config")->selectRow();
        $client = new PopHttpClient($config["appkey"], $config["secretKey"]);
        $request = new PddGoodsCatsGetRequest();

        $request->setParentCatId(0);
        try{
            $response = $client->syncInvoke($request);
        } catch(Com\Pdd\Pop\Sdk\PopHttpException $e){
            echo $e->getMessage();
            exit;
        }
        $content = $response->getContent();
        if(isset($content['error_response'])){
            echo "异常返回";
        }
        print_r($content);
        echo json_encode($content,JSON_UNESCAPED_UNICODE);
        */
        CC("taoke", "taoke_search")->onCategory();
    }
    public function onSharepic()
    {
        $userid=M("login")->userid;
        $user=M("user")->getUser($userid);
        $id=get_post("id", "i");
        $data=M("mod_taoke_searchcache")->selectRow(array(
            "where"=>"objectid=".$id
        ));
        if (empty($data)) {
            $this->goAll("产品已下架", 1);
        }
        $data=str2arr($data["content"]);
        ob_start();
        $content=HTTP_HOST."/module.php?m=taoke_pdd_search&a=show&id=".$id."&invite_uid=".$userid;
        $this->loadClass("qrcode", false, false);
        QRCODE::png($content, false, QR_ECLEVEL_L, 6);
        $ewmdata=ob_get_contents();
        ob_end_clean();
        ob_end_flush();
        $ewm=imagecreatefromString($ewmdata);
        $ewmx=imagesx($ewm);
        $ewmy=imagesy($ewm);
        
        $im=imagecreatetruecolor(400, 600);
        $white= imagecolorallocate($im, 250, 250, 250);
        imagefill($im, 0, 0, $white);
        //合并主图
        $pim=imagecreatefromString(file_get_contents(images_site($data["imgurl"])));
        $px=imagesx($pim);
        $py=imagesy($pim);
        imagecopyresampled($im, $pim, 0, 0, 0, 0, 400, 400, $px, $py);
        //创建半透明背景
        $whitebg = imagecolorallocatealpha($im, 250, 250, 250, 60);
        //imagefill($im, 0, 0, $white);
        //imagefilledrectangle($im,25,400,750,1175,$whitebg);
        //合并二维码
        imagecopyresampled($im, $ewm, 15, 400, 0, 0, 150, 150, $ewmx, $ewmy);
                 
        //创建头像昵称
        /*
        $uhead=imagecreatefromString(file_get_contents(images_site($user["user_head"].".100x100.jpg")));
        $uheadx=imagesx($uhead);
        $uheady=imagesy($uhead);
        imagecopyresampled($im,$uhead,225,1010,0,0,50,50,$uheadx,$uheady);
        */
        //文字
        $font=ROOT_PATH."/static/msyh.ttf";
        $black = imagecolorallocate($im, 32, 32, 32);
        //imagettftext($im, 16, 0, 185, 440, $black, $font, "我是".$user["nickname"]);
    
        $arr=preg_split("//u", $data["title"]);
        $title="";
        foreach ($arr as $k=>$v) {
            $title.=$v;
         
            if ($k%19==18) {
                $title.="\n";
            }
        }
        imagettftext($im, 10, 0, 165, 430, $black, $font, $title);
        $f60=imagecolorallocate($im, 255, 100, 0);
        $a60=imagecolorallocate($im, 60, 60, 60);
        imagettftext($im, 10, 0, 165, 500, $a60, $font, "原价");
        imagettftext($im, 10, 0, 202, 500, $a60, $font, "￥");
        imagettftext($im, 12, 0, 215, 500, $a60, $font, $data["price"]+$data["juan_money"]);
        imageline($im, 158, 495, 255, 495, $a60);
        imagettftext($im, 12, 0, 285, 500, $f60, $font, "领劵省");
        imagettftext($im, 12, 0, 335, 500, $f60, $font, "￥");
        imagettftext($im, 16, 0, 350, 500, $f60, $font, $data["juan_money"]);
        
        imagettftext($im, 12, 0, 165, 530, $f60, $font, "只要");
        imagettftext($im, 12, 0, 215, 530, $f60, $font, "￥");
        imagettftext($im, 16, 0, 230, 530, $f60, $font, $data["price"]);
        imagettftext($im, 10, 0, 45, 560, $a60, $font, "长按二维码识别");
        imagettftext($im, 10, 0, 165, 560, $a60, $font, "来自".$user["nickname"]."分享");
    
		$filename="attach/temp/taoke_sharepic.{$id}.{$userid}.jpg";
		if(!file_exists("attach/temp")){
			mkdir("attach/temp",0777);
		}	
		imagejpeg($im,$filename);
		header("Location: $filename");
    }
}
