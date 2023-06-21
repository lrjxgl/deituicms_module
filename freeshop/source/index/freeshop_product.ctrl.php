<?php
class freeshop_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function checkShop($userid){
		$shop=M("mod_freeshop_shop")->selectRow("userid=".$userid);
		if(empty($shop) || $shop["status"]!=1){
			$this->goAll("暂无权限",1);
		}
		return $shop;
	}
	
	public function onDefault(){
		
		$this->smarty->display("freeshop_product/index.html");
	}
	
	
	
	public function onSearch(){
		$this->smarty->display("freeshop_product/search.html");
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
			$cat=M("mod_freeshop_category")->selectRow("catid=".$catid);
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
		$list=MM("freeshop","freeshop_product")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"cat"=>$cat 
		));
		$this->smarty->display("freeshop_product/list.html");
	}
	
	public function onFollow(){
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		$userid=M("login")->userid;
		$rscount=true;
		$blogids=M("mod_freeshop_feeds")->selectCols(array(
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
		
		$list=MM("freeshop","freeshop_product")->Dselect($ops);
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
		$data=MM("freeshop","freeshop_product")->selectRow("productid=".$productid);
		if(!$data) $this->goAll("数据出错",1);
		$shop=MM("freeshop","freeshop_shop")->get($data["shopid"]);
		//浏览记录
		if($userid){
			$view=M("mod_freeshop_product_view")->selectRow("userid=".$userid." AND productid=".$productid);
			if(!$view){
				M("mod_freeshop_product_view")->insert(array(
					"userid"=>$userid,
					"productid"=>$productid,
					"shopid"=>$data["shopid"],
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("freeshop","freeshop_product")->update(array(
					"view_num"=>$data["view_num"]+1
				),"productid=".$productid);
			}
		}
		$data["parsecontent"]=MM("freeshop","freeshop_product")->parseContent($data["content"]);
		$data["etime_format"]=date("m-d H:i",$data["etime"]); 
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
		if($userid){
			$addr=M("user_lastaddr")->get($userid);
			$user=M("user")->selectRow(array(
				"where"=>" userid=".$userid,
				"fields"=>" userid,nickname,money"
			));
			 
		}
		if($data["ontime"]<time()){
			$data["status"]=2;
			 
		}
		$shareLink=HTTP_HOST."/module.php?m=freeshop_product&a=show&productid=".$productid."&share_userid=".$userid;
		$olist=M("mod_freeshop_order")->select(array(
			"where"=>" productid=".$productid." AND ispay=1 ",
			"order"=>"orderid DESC",
			"fields"=>"userid,createtime"
		));
		if($olist){
			foreach($olist as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($olist as $k=>$v){
				$u=$us[$v["userid"]];
				$u["createtime"]=$v["createtime"];
				$olist[$k]=$u;
			}
		}
		//邀请用户
		$share_userid=get("share_userid","i");
		if($share_userid){
			$_SESSION["ss_share_userid"]=$share_userid;
		}
		$this->smarty->goAssign(array(
			 
			"data"=>$data,
			"comment_objectid"=>$id,
			"comment_tablename"=>"mod_freeshop_product",
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
			"olist"=>$olist
		));
		$this->smarty->display("freeshop_product/show.html");
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
		$list=MM("freeshop","freeshop_product")->Dselect($ops,$rscount);
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
		$config=M("mod_freeshop_config")->selectRow("1");
		
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"sconfig"=>$config
			 
		));
		$this->smarty->display("freeshop_product/my.html");
	}
	public function checkPost($shopid){
		$config=M("mod_freeshop_config")->selectRow("1");
		$shopMoney=MM("freeshop","freeshop_shop_money")->get($shopid);
		if($shopMoney["balance"]<$config["post_money"]){
			$this->goAll("余额不足，请先充值",1);
		}
		$postNum=M("mod_freeshop_product")->selectOne(array(
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
		$this->checkPost($shop["shopid"]);
		$timeList=$this->timeList();
		$config=M("mod_freeshop_config")->selectRow("1");
		$catList=MM("freeshop","freeshop_product")->catList();
		$this->smarty->assign(array(
			"sconfig"=>$config, 
			"timeList"=>$timeList,
			"catList"=>$catList
		));
		$this->smarty->display("freeshop_product/add.html");
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
		$config=M("mod_freeshop_config")->selectRow("1");
		$data=M("mod_freeshop_product")->postData();
		$data["content"]=$content=post("content","h");
		if(empty($data["content"])){
			$this->goAll("请输入产品内容",1);
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
		$data["status"]=1;
		$data["userid"]=$userid;
		$data["shopid"]=$shopid;
		$data["createtime"]=date("Y-m-d H:i:s");
		$timeList=$this->timeList();
		$data["etime"]=time()+$timeList[$data["freetime"]]["num"]*60;
		$data["ontime"]=max(time()+900,$data["etime"]-1800);
		if($data["price"]==0 || $data["market_price"]==0){
			$this->goAll("请设置价格",1);
		}
		$data["discount"]=$data["price"]*10/$data["market_price"];
		if($data["maxnum"]<$config["min_num"] || $data["maxnum"]>$config["max_num"]){
			$this->goAll("数量需介于".$config["min_num"]."和".$config["max_num"]."之间",1);
		}
		if($data["discount"]<$config["min_discount"] || $data["discount"]>$config["max_discount"]){
			$this->goAll("折扣需介于".$config["min_discount"]."和".$config["max_discount"]."之间",1);
		}
		/*扣除费用*/
		MM("freeshop","freeshop_shop_money")->addMoney(array(
			"shopid"=>$shopid,
			"balance"=>-$config["post_money"],
			"content"=>"发布产品扣除".$config["post_money"]."元"
		));
		$productid=M("mod_freeshop_product")->insert($data);
		 
		//推送到订阅
		
		$us=M("mod_freeshop_follow")->selectCols(array(
			"fields"=>"userid",
			"where"=>"shopid=".$shopid,
			"limit"=>100000000
		));
	
		if(!$us) $us=array();
		 
		foreach($us as $uid){
			M("mod_freeshop_feeds")->insert(array(
				"userid"=>$uid,
				"productid"=>$productid,
				"shopid"=>$shopid,
				"dateline"=>time(),
			));
		}
		
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		$userid=M("login")->userid;
		$id=get("productid","i");
		$row=M("mod_freeshop_product")->selectRow("productid=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		
		M("mod_freeshop_product")->update(array("status"=>11),"productid=".$id);
		 
		//删除所有关注的
		M("mod_freeshop_feeds")->delete("productid=".$id);
		 
		 
		$this->goAll("删除成功");
	}
	
	public function onrecommend(){
		$userid=M("login")->userid;
		$id=get("productid","i");
		$row=M("mod_freeshop_product")->selectRow("productid=".$id);
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
		$config=M("mod_freeshop_config")->selectRow("1");
		$shopMoney=MM("freeshop","freeshop_shop_money")->get($shopid);
		if($shopMoney["balance"]<$config["recommend_money"]){
			$this->goAll("余额不足，请先充值",1);
		}
		/*扣除费用*/
		MM("freeshop","freeshop_shop_money")->addMoney(array(
			"shopid"=>$shopid,
			"balance"=>-$config["recommend_money"],
			"content"=>"上热门扣除".$config["recommend_money"]."元"
		));
		M("mod_freeshop_product")->update(array(
			"isrecommend"=>1
		),"productid=".$id);
		 
		$this->goAll("上热门成功");
	}
	
	public function onCopy(){
		$userid=M("login")->userid;
		$shop=$this->checkShop($userid);
		$shopid=$shop["shopid"];
		$id=get_post("productid","i");
		$data=M("mod_freeshop_product")->selectRow("productid=".$id);
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
		$timeList=$this->timeList();
		$config=M("mod_freeshop_config")->selectRow("1");
		$catList=MM("freeshop","freeshop_product")->catList();
		$this->smarty->assign(array(
			"sconfig"=>$config, 
			"timeList"=>$timeList,
			"catList"=>$catList,
			"data"=>$data,
			"imgsdata"=>$imgsdata
		)); 
		 
		$this->smarty->display("freeshop_product/copy.html");
	}
}