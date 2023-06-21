<?php
class car_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function  ios(){
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
		    return true;
		}
		return false;
	} 
	public function checkShop($userid){
		$shop=M("mod_car_shop")->selectRow("userid=".$userid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		return $shop;
	}
	
	public function onDefault(){
		 
		$this->smarty->display("car_product/index.html");
	}
	
	
	
	public function onSearch(){
		$this->smarty->display("car_product/search.html");
	}
	public function onList(){
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=12;
		$type=get("type","h");
		$order=" productid DESC";
		$fields=" * ";
		switch($type){
			case "new":
				$where=" status=1 ";
				break;
			case "history":
				$where=" status=2 ";
				break;
			case "hot":
				$where="status=1 AND createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."' ";
				$order="comment_num DESC";
				break;
			case "recommend":
				$where=" status=1 AND isrecommend=1 ";
				break;
			case "near":
				$where="status=1  ";
				$gps=gps_get();
				$lat=$gps['lat'];
				$lng=$gps['lng'];
				 
				if($lat && $lng){
					$fields.=",".' ROUND(  
						6378.138 * 2 * ASIN(  
							SQRT(  
								POW(  
									SIN(  
										(  
											'.$lat.' * PI() / 180 - lat * PI() / 180  
										) / 2  
									),  
									2  
								) + COS('.$lat.' * PI() / 180) * COS(lat * PI() / 180) * POW(  
									SIN(  
										(  
											'.$lng.' * PI() / 180 - lng * PI() / 180  
										) / 2  
									),  
									2  
								)  
							)  
						)  
					) AS distance_num  ';
					$order=" distance_num ASC";
					
				} 
				break;
			case "follow":
				$this->onFollow(); 
				break;
			case "recommend":
				$where="status=1 AND  isrecommend=1 ";
				break;
			default:
				$where="status=1  ";
				
				break;
		}
		$catid=get("catid","i");
		$cat=false;
		if($catid){
			$where.=" AND catid=".$catid;
			$url.="&catid=".$catid;
			$cat=M("mod_car_category")->selectRow("catid=".$catid);
		}
		//品牌
		$brandid=get("brandid","i");
		if($brandid){
			$where.=" AND brandid=".$brandid;
			$url.="&brandid=".$brandid;
		}
		//价格区间
		$choicePrice=get("choicePrice","h");
		if(!empty($choicePrice)){
			$arr=explode("-",$choicePrice);
			$min=floatval($arr[0])*10000;
			$max=floatval($arr[1])*10000;
			if($max>$min){
				$where.=" AND (money>".$min." AND money<".$max." ) ";
			}else{
				$money=intval($choicePrice)*10000;
				if(preg_match("/以上/",$choicePrice)){
					$where.=" AND money>=".$money;
				}
				if(preg_match("/以下/",$choicePrice)){
					$where.=" AND money<=".$money;
				}
			}
			
			$url.="&choicePrice=".$choicePrice;
		}
		 
		$orderby=get("orderby","h");
		switch($orderby){
			case "buy_num":
				$order="buynum DESC";
				break;
			case "discount":
				$order="discount ASC";
				break;
		}
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit,
			"fields"=>$fields
		);
		$rscount=true;
		$list=MM("car","car_product")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		//
		$catList=MM("car","car_product")->catList();
		$brandList=MM("car","car_brand")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>"orderindex ASC"
		));
		$yearList=array(
			3=>"三年内",
			5=>"五年内",
			7=>"七年内",
			10=>"十年内"
		);
		$priceList=array(
			"3万以下",
			"3-5万",
			"5-7万",
			"7-10万",
			"10万以上"
			
		);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"cat"=>$cat,
			"catList"=>$catList,
			"brandList"=>$brandList,
			"yearList"=>$yearList,
			"priceList"=>$priceList
		));
		$this->smarty->display("car_product/list.html");
	}
	
	public function onFollow(){
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		$userid=M("login")->userid;
		$rscount=true;
		$blogids=M("mod_car_feeds")->selectCols(array(
			"where"=>" userid=".$userid,
			"fields"=>"productid",
			"order"=>" dateline DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if(!$blogids){
			$blogids=[0]; 
		} 
		$ops=array(
			"where"=>" status=1 AND  productid in("._implode($blogids).") ",
			"order"=>"  productid DESC"
		);
		
		$list=MM("car","car_product")->Dselect($ops);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist
			 
		));
	} 
	 
	public function onShow(){
		$productid=get('productid','i');
		$userid=M("login")->userid;
		$data=MM("car","car_product")->selectRow("productid=".$productid);
		if(!$data) $this->goAll("数据出错",1);
		$shop=MM("car","car_shop")->get($data["shopid"]);
		//浏览记录
		if($userid){
			$view=M("mod_car_product_view")->selectRow("userid=".$userid." AND productid=".$productid);
			if(!$view){
				M("mod_car_product_view")->insert(array(
					"userid"=>$userid,
					"productid"=>$productid,
					"shopid"=>$data["shopid"],
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("car","car_product")->update(array(
					"view_num"=>$data["view_num"]+1
				),"productid=".$productid);
			}
		}
		 
		//图集
		$imgslist=array();
		if($data['imgsdata']){
			$imgs=explode(",",$data['imgsdata']);
			foreach($imgs as $img){
				$imgslist[]=images_site($img);
			}			  
		}
		//视频
		$data["mp4url"]=images_site($data["mp4url"]);
		//是否点赞
		$islove=0;
		$love=M("love")->selectRow("tablename='mod_car_product' AND userid=".$userid." AND objectid=".$productid);
		if($love){
			$islove=1;
		}
		//是否收藏
		$isfav=0;
		$fav=M("fav")->selectRow("tablename='mod_car_product' AND userid=".$userid." AND objectid=".$productid);
		if($fav){
			$isfav=1;
		}
		$shareLink=HTTP_HOST."/module.php?m=car_product&a=show&productid=".$productid."&share_userid=".$userid;
		 
		//邀请用户
		$share_userid=get("share_userid","i");
		$flConfig=M("mod_car_config")->selectRow("1");
		if($share_userid && $data["hb_on"] && $flConfig["hb_on"]){
			$sendwx=true;
			if($flConfig["hb_wx_follow"]){
				$sendwx=M("weixin")->checkFollow($share_userid);
			}
			if($sendwx){
				MM("car","car_hongbao")->addHongBao(array(
					"userid"=>$share_userid,
					"fid"=>$productid
				),$data);
			}
				
		}
		$hbList=array();
		if($data["hb_on"]){
			$hbList=MM("car","car_hongbao")->Dselect(array(
				"where"=>" fid=".$productid,
				"order"=>"id DESC"
			));
		}
		$brand=M("mod_car_brand")->selectRow("brandid=".$data["brandid"]);
		$this->smarty->goAssign(array(
			 
			"data"=>$data,
			"comment_objectid"=>$productid,
			"comment_tablename"=>"mod_car_product",
			"comment_f_userid"=>$data['userid'],
			"imgslist"=>$imgslist,
			"shop"=>$shop,
			"userid"=>$userid,
			"etime"=>$data["etime"]-time(),
			"addr"=>$addr,
			"seo"=>array(
				"title"=>$data["content"]
			),
			"shareLink"=>$shareLink,
			"olist"=>$olist,
			"brand"=>$brand,
			"isfav"=>$isfav,
			"islove"=>$islove
		));
		$this->smarty->display("car_product/show.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		
		$start=get("per_page","i");
		$limit=24;
		
		$type=get('type',"h");
		switch($type){
			
			case "online":
				$where=" status=1 ";
				break;
			case "offline":
				$where=" status=2 ";
				break;
			default:
				$where=" status in(0,1,2) ";
				break;
		}
		$where.="   AND shopid=".$shop["shopid"];
		$ops=array(
			"where"=>$where,
			"order"=>" productid DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("car","car_product")->Dselect($ops,$rscount);
		$timeList=$this->timeList(); 
		if($list){
			foreach($list as $k=>$v){
				$v["freetime_title"]=$timeList[$v["freetime"]]["title"];
				$list[$k]=$v;		
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$config=M("mod_car_config")->selectRow("1");
		//红包
		$hbList=M("mod_car_hbconfig")->select(array(
			"where"=>" status=1",
			"order"=>"hb_money DESC"
		));
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"sconfig"=>$config,
			"hbList"=>$hbList 
		));
		$this->smarty->display("car_product/my.html");
	}
	public function checkPost($shopid){
		$config=M("mod_car_config")->selectRow("1");
		$shopMoney=MM("car","car_shop_money")->get($shopid);
		if($shopMoney["balance"]<$config["post_money"]){
			$this->goAll("余额不足，请先充值",1);
		}
		$postNum=M("mod_car_product")->selectOne(array(
			"where"=>"shopid=".$shopid." AND status=1 ",
			"fields"=>"count(*) as c"
		));
		if($postNum>=$config["post_num"]){
			$this->goAll("您上线的产品超过".$config["post_num"]."条",1);
		}
	}
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$this->checkPost($shop["shopid"]);
		$timeList=$this->timeList();
		$config=M("mod_car_config")->selectRow("1");
		$catList=MM("car","car_product")->catList();
		$brandList=MM("car","car_brand")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		$id=get_post("productid","i");
		$imgsdata=array();
		if($id){
			$data=M("mod_car_product")->selectRow("productid=".$id);
			if($data["status"]>0){
				$this->goAll("已发布不能编辑",1);
			}
			if($data["shopid"]!=$shopid){
				$this->goAll("暂无权限",1);
			}
			$data["true_mp4url"]=images_site($data["mp4url"]);
			
			if($data["imgsdata"]){
					$imgs=explode(",",$data["imgsdata"]);
					foreach($imgs as $v){
						$imgsdata[]=array(
							"imgurl"=>$v,
							"trueimgurl"=>images_site($v)
						);
					}
			}
		}
		
		 
		
		$this->smarty->goassign(array(
			"data"=>$data,
			"sconfig"=>$config, 
			"timeList"=>$timeList,
			"catList"=>$catList,
			"brandList"=>$brandList,
			"imgsdata"=>$imgsdata
		));
		$this->smarty->display("car_product/add.html");
	}
	public function timeList(){
		return array(
			1=>["num"=>30,"title"=>"30分钟"],
			2=>["num"=>60,"title"=>"60分钟"],
			3=>["num"=>120,"title"=>"2小时"],
			4=>["num"=>180,"title"=>"3小时"],
			5=>["num"=>720,"title"=>"12小时"],
			6=>["num"=>1440,"title"=>"24小时"],
			7=>["num"=>2880,"title"=>"48小时"],
			
		);
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$this->checkPost($shop["shopid"]);
		$config=M("mod_car_config")->selectRow("1");
		$data=M("mod_car_product")->postData();
		$data["content"]=$content=post("content","x");
		if(empty($data["content"])){
			$this->goAll("请输入产品内容",1);
		}
		if(empty($data["title"])){
			$this->goAll("请输入主题",1);
		}
		if(empty($data["pai_date"])){
			$this->goAll("请设置上牌时间",1);
		}
		$imgsdata=post("imgsdata","h");
		if($imgsdata){
			$ims=explode(",",$imgsdata);
			foreach($ims as $im){
				if($im!="undefined" && $im!=""){
					$imgs[]=$im;
				}
			}
			if(!empty($imgs)){
				$data["imgurl"]=$imgs[0];
				$data["imgsdata"]=implode(",",$imgs);
			}	
		}
		$data["lat"]=$shop["lat"];
		$data["lng"]=$shop["lng"];
		$data["status"]=0;
		$data["userid"]=$userid;
		$data["shopid"]=$shopid;
		 
		if($data["money"]==0){
			$this->goAll("请设置价格",1);
		}
		$productid=post("productid","i");
		$data["updatetime"]=date("Y-m-d H:i:s");
		if($productid){
			
			M("mod_car_product")->update($data,"productid=".$productid);
		}else{
			$data["status"]=1;
			$data["createtime"]=date("Y-m-d H:i:s");
			/*扣除费用*/
			MM("car","car_shop_money")->addMoney(array(
				"shopid"=>$shopid,
				"balance"=>-$config["post_money"],
				"content"=>"发布产品扣除".$config["post_money"]."元"
			));
			$productid=M("mod_car_product")->insert($data);
			 
			//推送到订阅
			
			$us=M("mod_car_follow")->selectCols(array(
				"fields"=>"userid",
				"where"=>"shopid=".$shopid,
				"limit"=>100000000
			));
				
			if(!$us) $us=array();
			 
			foreach($us as $uid){
				M("mod_car_feeds")->insert(array(
					"userid"=>$uid,
					"productid"=>$productid,
					"shopid"=>$shopid,
					"dateline"=>time(),
				));
			}
		}
		
		
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		$userid=M("login")->userid;
		$id=get("productid","i");
		$row=M("mod_car_product")->selectRow("productid=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		
		M("mod_car_product")->update(array(
			"status"=>11,
			"updatetime"=>date("Y-m-d H:i:s")
		),"productid=".$id);
		 
		//删除所有关注的
		M("mod_car_feeds")->delete("productid=".$id);
		 
		 
		$this->goAll("删除成功");
	}
	
	public function onrecommend(){
		$userid=M("login")->userid;
		$id=get("productid","i");
		$row=M("mod_car_product")->selectRow("productid=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		if($row["status"]!=1){
			$this->goAll("商品已下架",1);
		}
		if($row["isrecommend"]==1){
			$this->goAll("已推荐",1);
		}
		$shopid=$row["shopid"];
		$config=M("mod_car_config")->selectRow("1");
		$shopMoney=MM("car","car_shop_money")->get($shopid);
		if($shopMoney["balance"]<$config["recommend_money"]){
			$this->goAll("余额不足，请先充值",1);
		}
		/*扣除费用*/
		MM("car","car_shop_money")->addMoney(array(
			"shopid"=>$shopid,
			"balance"=>-$config["recommend_money"],
			"content"=>"上热门扣除".$config["recommend_money"]."元"
		));
		M("mod_car_product")->update(array(
			"isrecommend"=>1,
			"updatetime"=>date("Y-m-d H:i:s")
		),"productid=".$id);
		 
		$this->goAll("上热门成功");
	}
	
	public function onCopy(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$id=get_post("productid","i");
		$data=M("mod_car_product")->selectRow("productid=".$id);
		if($data["shopid"]!=$shopid){
			$this->goAll("暂无权限",1);
		}
		 
		$imgsdata=array();
		if($data["imgsdata"]){
				$imgs=explode(",",$data["imgsdata"]);
				foreach($imgs as $v){
					$imgsdata[]=array(
						"imgurl"=>$v,
						"trueimgurl"=>images_site($v)
					);
				}
		}
		$data["true_mp4url"]=images_site($data["mp4url"]);
		$timeList=$this->timeList();
		$config=M("mod_car_config")->selectRow("1");
		$catList=MM("car","car_product")->catList();
		$brandList=MM("car","car_brand")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		$this->smarty->goassign(array(
			"sconfig"=>$config, 
			"timeList"=>$timeList,
			"catList"=>$catList,
			"data"=>$data,
			"imgsdata"=>$imgsdata,
			"brandList"=>$brandList
		)); 
		 
		$this->smarty->display("car_product/copy.html");
	}
	
	public function onStatus(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$id=get_post("productid","i");
		$data=M("mod_car_product")->selectRow("productid=".$id);
		if($data["shopid"]!=$shopid){
			$this->goAll("暂无权限",1);
		}
		if($data["status"]>2){
			$this->goAll("禁止上架",1);
		}
		if($data["status"]==1){
			$status=2;
		}else{
			$status=1;
		}
		M("mod_car_product")->update(array(
			"status"=>$status,
			"updatetime"=>date("Y-m-d H:i:s")
		),"productid=".$id);
		$this->goAll("修改成功",0,$status);
	}
	
	public function onHbPay($return=0){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
 
		$orderno="Re".M("maxid")->get();
		$productid=get("productid",'i');
		$hbid=get("hbid","i");
		$hongbao=M("mod_car_hbconfig")->selectRow("id=".$hbid);
		$car=M("mod_car_product")->selectRow("productid=".$productid);
		if($car["hb_on"]){
			$this->goAll("已经设置红包了",1);
		}
		$money= $hongbao["hb_money"]*1.01;
		$shopid=$car["shopid"];
		$config=M("mod_car_config")->selectRow("1");
		$shopMoney=MM("car","car_shop_money")->get($shopid);
		if($shopMoney["balance"]<$money){
			$this->goAll("余额不足，请先充值",1);
		}
		/*扣除费用*/
		MM("car","car_shop_money")->addMoney(array(
			"shopid"=>$shopid,
			"balance"=>-$money,
			"content"=>"上热门扣除".$money."元"
		));
		M("mod_car_product")->update(array(
			"hb_num"=>$hongbao["hb_num"],
			"hb_on"=>1,
			"hb_money"=>$hongbao["hb_money"],
			"updatetime"=>date("Y-m-d H:i:s")
		),"productid=".$productid);
		$this->goALl("success");
	} 
}