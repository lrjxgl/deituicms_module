<?php
class car_sold_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		
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
		$where.=" AND userid=".$userid;
		$ops=array(
			"where"=>$where,
			"order"=>" productid DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("car","car_sold_product")->Dselect($ops,$rscount);
	 
		if($list){
			foreach($list as $k=>$v){
				 
				$list[$k]=$v;		
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			 
		));
		$this->smarty->display("car_sold_product/my.html");
	}
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		 
		$config=M("mod_car_config")->selectRow("1");
		$catList=MM("car","car_sold_product")->catList();
		$brandList=MM("car","car_brand")->Dselect(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		$id=get_post("productid","i");
		$imgsdata=array();
		if($id){
			$data=M("mod_car_sold_product")->selectRow("productid=".$id);
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
		
		 
		
		$this->smarty->goAssign(array(
			"data"=>$data,
			"sconfig"=>$config, 
			"timeList"=>$timeList,
			"catList"=>$catList,
			"brandList"=>$brandList,
			"imgsdata"=>$imgsdata
		));
		$this->smarty->display("car_sold_product/add.html");
	}
	
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		 
		$config=M("mod_car_config")->selectRow("1");
		$data=M("mod_car_sold_product")->postData();
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
	 
		$data["status"]=0;
		$data["userid"]=$userid;
		 
		if($data["money"]==0){
			$this->goAll("请设置价格",1);
		}
		$productid=post("productid","i");
		if($productid){
			M("mod_car_sold_product")->update($data,"productid=".$productid);
		}else{
			$productid=M("mod_car_sold_product")->insert($data);
			 
		}
		
		
		$this->goAll("发布成功");
	}
	
	public function onStatus(){
		$userid=M("login")->userid;
		 
		$id=get_post("productid","i");
		$data=M("mod_car_sold_product")->selectRow("productid=".$id);
		if($data["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		if($data["status"]==1){
			$status=2;
		}else{
			$status=1;
		}
		M("mod_car_sold_product")->update(array(
			"status"=>$status
		),"productid=".$id);
		$this->goAll("修改成功",0,$status);
	}
	
	
}
