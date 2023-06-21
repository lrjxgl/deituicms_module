<?php
class taoke_searchControl extends skymvc{
	
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
		$this->smarty->display("taoke_search/index.html");
	}
	
	public function onList(){
		$word=get("word");
		$catmap=get("catmap","h");
		if($catmap=='50010850,50008899'){
			$word="衣服";
		}elseif($catmap=='50018813'){
			$word="美食";
		}elseif($catmap=='50012032'){
			$word="鞋包";
		}
		$this->smarty->goAssign(array(
			"catmap"=>$catmap,
			"word"=>$word
		));
		$this->smarty->display("taoke_search/list.html");
	}
	
	public function onGaofan(){
		$word=get("word");
		$catmap=get("catmap","h");
		$this->smarty->goAssign(array(
			"catmap"=>$catmap,
			"word"=>$word
		));
		$this->smarty->display("taoke_search/gaofan.html");
	}
	public function onSearchApi(){
		include_once ROOT_PATH."/module/taoke/sdk/TopSdk.php";
		 
		$config=M("mod_taoke_config")->selectRow();
		$c = new TopClient;
		$app_key=$c->appkey = $config['appkey'];
		$secret=$c->secretKey = $config['secretKey'];
		$c->format="json";
		//搜索
		$word=get("word");
		$req = new TbkDgMaterialOptionalRequest;
		$req->setAdzoneId($config["zoneid"]);
		if($word){
			$req->setQ($word);
		}
		$limit=24;
		$req->setHasCoupon("true");
		$req->setPageSize($limit);
		$startPage=get("per_page","i");
		$startPage++;
		$req->setPageNo($startPage);
		//牛皮癣
		$req->setNpxLevel("2");
		//佣金比例
		$req->setEndTkRate("1500");
		//$req->setStartTkRate("500");
		//分类
		$catmap=get("catmap","h");
		if($catmap!='' && $catmap!='undefined' ){
			$req->setCat($catmap);
		}else{
			if($word==''){
				$req->setCat("16,18");
			}
			
		}		 
		//排序
		$orderby=get("orderby","h");
		switch($orderby){
			case "sold_num":
				$req->setSort("total_sales_des");
				break;
			case "priceAsc":
				$req->setSort("price_asc");
				break;
			case "maxBack":
				
				$req->setSort("tk_rate_des");
				break;
		}
		if($config["shoptype"]==1){
			$req->setIsTmall("true");
		}
		
		/*
		$req->setStartDsr("10");
		$req->setPageNo("1");
		$req->setPlatform("1");
		$req->setEndTkRate("1534");
		$req->setStartTkRate("334");
		$req->setEndPrice("10");
		$req->setStartPrice("10");
		$req->setIsOverseas("false");
		
		$req->setSort("tk_rate_des");
		$req->setItemloc("杭州");
		$req->setCat("16,18");
		*/
		//物料
		//$req->setMaterialId("2836");
		//$req->setHasCoupon("false");
		//$req->setIp("13.2.33.4");
		
		//$req->setNeedFreeShipment("true");
		//$req->setNeedPrepay("true");
		//$req->setIncludePayRate30("true");
		//$req->setIncludeGoodRate("true");
		//$req->setIncludeRfdRate("true");
	 
		//$req->setEndKaTkRate("1234");
		//$req->setStartKaTkRate("1234");
		//$req->setDeviceEncrypt("MD5");
		//$req->setDeviceValue("xxx");
		//$req->setDeviceType("IMEI");
		//$req->setLockRateEndTime("1567440000000");
		//$req->setLockRateStartTime("1567440000000");
		$resp = $c->execute($req);
		
		$resp=json_decode(json_encode($resp),true);
		 
		$oids=array();
		if(isset($resp["result_list"])){
			$list=$resp["result_list"]["map_data"];
			foreach($list as $k=>$v){
				
				$v["price"]=intval(($v["zk_final_price"]-$v["coupon_amount"])*100)/100;
			 
				$oids[]=$v["item_id"];
				$p=array(
					"id"=>$v["item_id"],
					"title"=>sql($v["title"]),
					"imgurl"=>sql($v["pict_url"]),
					"description"=>sql($v["item_description"]),					
					"juan_money"=>$v["coupon_amount"],
					"coupon_end_time"=>$v["coupon_end_time"],
					"coupon_start_time"=>$v["coupon_start_time"],
					"yj_bl"=>$v["commission_rate"],//佣金比例
					"yj_money"=>intval($v["price"]*$v["commission_rate"])/10000,
					"sold_num"=>$v["volume"],
					"price"=>$v["price"],
					"imgList"=>$v["small_images"]["string"],
					"juan_url"=>$v["coupon_share_url"],
					"shop_title"=>sql($v["shop_title"]),
					"shop_dsr"=>sql($v["shop_dsr"]),
					"user_type"=>$v["user_type"]
				);
				$list[$k]=$p;
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
							"etime"=>$v["coupon_end_time"],
							"k"=>"taobao",
							"title"=>$v["title"],
							"yj_bl"=>$v["yj_bl"],//佣金比例
							"yj_money"=>$v["yj_money"],
							"sold_num"=>$v["sold_num"],
							"price"=>$v["price"],
							"juan_money"=>$v["juan_money"],
							"imgurl"=>$v["imgurl"],
							"createtime"=>time()
						);
					}	
			 	}
			 	M("mod_taoke_searchcache")->insertMore($indata);
			 }
		}else{
			if(get("ajax")){
				
				if(!get("researchtwo")){
					$_GET["researchtwo"]=1;
					$this->onSearchApi();
				}
				
				$this->goAll("网络开小差了,下拉刷新一下吧",1,$resp);
			}
			
		}
		
		if($startPage>100){
			$startPage=0;
		}
		if(count($list)<$limit){
			$startPage=0;
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$startPage,
			"catmap"=>$catmap,
			"word"=>$word
		));
		$this->smarty->display("taoke_search/index.html");
	}
	
	public function onShow(){
		$id=get("id","i");
		$data=M("mod_taoke_searchcache")->selectRow("objectid=".$id);
		if(empty($data)){
			$this->goAll("商品已下架",1);
		}
		$userid=M("login")->userid;
		$love_num=$data["love_num"];
		$data=str2arr($data["content"]);
		$data["love_num"]=$love_num;
		M("mod_taoke_searchcache")->changenum("view_num",1,"objectid=".$id);
		$islove=0;
		$fav=M("mod_taoke_love")->selectRow("productid=".$id." AND userid=".$userid." AND k='taobao' ");
		if($fav){
			$islove=1;
		}
		$this->smarty->goAssign(array(
			"invite_uid"=>$userid,
			"data"=>$data,
			"islove"=>$islove,
			"sharePic"=>HTTP_HOST."/module.php?m=taoke_search&a=sharepic&nologin=1&id=".$id."&invite_uid=".$userid,
			"shareUrl"=>HTTP_HOST."/module.php?m=taoke_search&a=show&id=".$id."&invite_uid=".$userid
		));
		$this->smarty->display("taoke_search/show.html");
	}
	public function onGetPwd(){
		$id=get("id","i");
		$data=M("mod_taoke_searchcache")->selectRow("objectid=".$id);
		
		$pro=str2arr($data["content"]);
		include ROOT_PATH."/module/taoke/sdk/TopSdk.php";
		$config=M("mod_taoke_config")->selectRow();
		$c = new TopClient;
		$c->format="json";
		$app_key=$c->appkey = $config['appkey'];
		$secret=$c->secretKey = $config['secretKey'];
		//end 
		$req = new TbkTpwdCreateRequest;
		$userid=M("login")->userid;
		$req->setUserId($userid);
		$req->setText($pro['title']);
		$req->setUrl("https:".$pro['juan_url']);
		$req->setLogo($pro['imgurl']);
		$resp = $c->execute($req);
		$resp=json_decode(json_encode($resp),true);
	 
		$juan_pwd=$resp['data']['model'];
		if($juan_pwd){
			$this->goAll("success",0,$juan_pwd);
		}else{
			$this->goAll("优惠券已经没咯",1);
		}
	}
	
	public function oncategory(){
		$plist=array(
			1=>"nvzhuang",
			10=>"nanzhuang",
			7=>"yundong",
			2=>"baihuo",
			4=>"shouji",
			9=>"meizhuang",
			6=>"meishi",
			18=>"shengxian",
			19=>"shuma",
			3=>"muying",
			5=>"xiexue",
			14=>"xihu",
			11=>"xiangbao",
			13=>"shipin",
			12=>"neiyi",
			8=>"jiazhuang",
			15=>"dianqi",
			20=>"qiye",
			16=>"chepin",
			17=>"baojian"
		);
		$pid=get("pid","i");
		if($pid==0){
			$pid=1;
		}
		$res=file_get_contents(ROOT_PATH."/module/taoke/taobao_category/".$plist[$pid].".json");
		$json=json_decode($res,true);
		$list=$json["data"]["result"][0];
		
		$this->smarty->goAssign(array(
			"industryList"=>$list["industryList"],
			"moduleList"=>$list["moduleList"],
		));
		$this->smarty->display("taoke_search/category.html");
	}
	
	public function onSharepic(){
		$userid=M("login")->userid;
		if(!$userid){
			$userid=get("invite_uid","i");
		}
		$user=M("user")->getUser($userid);
		$id=get_post("id","i");
		$data=M("mod_taoke_searchcache")->selectRow(array(
			"where"=>"objectid=".$id
		));
		if(empty($data)){
			$this->goAll("产品已下架",1);
		}
		$data=str2arr($data["content"]);
		ob_start();
		$content=HTTP_HOST."/module.php?m=taoke_search&a=show&id=".$id."&invite_uid=".$userid;
		$this->loadClass("qrcode",false,false);
		QRCODE::png($content,false,QR_ECLEVEL_L,6);
		$ewmdata=ob_get_contents();		
		ob_end_clean();
		ob_end_flush();
		$ewm=imagecreatefromString($ewmdata);
		$ewmx=imagesx($ewm);
		$ewmy=imagesy($ewm);
		
		$im=imagecreatetruecolor(400,600);
		$white= imagecolorallocate($im,250,250,250);
		imagefill($im,0,0,$white);
		//合并主图
		$pim=imagecreatefromString(file_get_contents(images_site($data["imgurl"])));
		$px=imagesx($pim);
		$py=imagesy($pim);
		imagecopyresampled($im,$pim,0,0,0,0,400,400,$px,$py);	
		//创建半透明背景
		$whitebg = imagecolorallocatealpha($im,250,250,250,60);
		//imagefill($im, 0, 0, $white);
		//imagefilledrectangle($im,25,400,750,1175,$whitebg);
		//合并二维码
		imagecopyresampled($im,$ewm,15,400,0,0,150,150,$ewmx,$ewmy);		
				 
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
	
		$arr=preg_split("//u",$data["title"]);
		$title="";	
		foreach($arr as $k=>$v){
			$title.=$v;
		 
			if($k%19==18){
				$title.="\n";
			}
		}	
		imagettftext($im, 10, 0, 165, 430, $black, $font, $title);
		$f60=imagecolorallocate($im, 255,100,0);
		$a60=imagecolorallocate($im, 60,60,60);
		imagettftext($im, 10, 0, 165, 500, $a60, $font, "原价");
		imagettftext($im, 10, 0, 202, 500, $a60, $font, "￥");
		imagettftext($im, 12, 0, 215, 500, $a60, $font, $data["price"]+$data["juan_money"]);
		imageline($im,158,495,255,495,$a60);
		imagettftext($im, 12, 0, 285, 500, $f60, $font, "领劵省");
		imagettftext($im, 12, 0, 335, 500, $f60, $font, "￥");
		imagettftext($im, 16, 0, 350, 500, $f60, $font, $data["juan_money"]);
		
		imagettftext($im, 12, 0, 165, 530, $f60, $font, "只要");
		imagettftext($im, 12, 0, 215, 530, $f60, $font, "￥");
		imagettftext($im, 16, 0, 230, 530, $f60, $font,$data["price"]);
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
?>