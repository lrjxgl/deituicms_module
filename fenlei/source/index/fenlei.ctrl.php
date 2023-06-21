<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fenleiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function  ios(){			
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
			    return true;
			}
			return false;
		} 
		public function onDefault(){
			 
			$recList=MM("fenlei","fenlei_tags")->getfenleiByKey("recommend");
			$newList=MM("fenlei","fenlei_tags")->getfenleiByKey("new");
			$catList=MM("fenlei","fenlei_category")->children(0,1);
			$articleList=M("article_tags")->getarticleByKey("fenlei-recommend");
			$indexList=MM("fenlei","fenlei")->Dselect(array(
				"where"=>" status=1 AND isindex=1 ",
				"order"=>"isindex_etime DESC",
				"limit"=>48
			));
			$list=[];
			if($indexList){
				foreach($indexList as $v)
				$list[$v["id"]]=$v;
			}
			if($newList){
				foreach($newList as $v){
					if(!isset($list[$v["id"]])){
						$list[$v["id"]]=$v;
					}
					
				}
			}
			//广告轮显
			$fromapp=get("fromapp");
			switch($fromapp){
				case "uniapp":
					$flashList=M("ad")->listByNo("uniapp-fenlei-index");
					$adList=M("ad")->listByNo("uniapp-fenlei-ad");
					$navList=M("ad")->listByNo("uniapp-fenlei-nav"); 
					break;
				default:
					$flashList=M("ad")->listByNo("wap-fenlei-index");
					$adList=M("ad")->listByNo("wap-fenlei-ad");
					$navList=M("ad")->listByNo("wap-fenlei-nav"); 
					break;
			}
			$seo=M("seo")->get("fenlei","default");
			$site=M("site")->get(); 
			$this->smarty->goassign(
				array(
					"seo"=>$seo,
					"list"=>$list,
					"recList"=>$recList,
					"catList"=>$catList,
					"articleList"=>$articleList,
					"url"=>$url,
					"flashList"=>$flashList,
					"adList"=>$adList,
					"navList"=>$navList,
					"site"=>$site
				)
			);
			$tpl=M("pagetpl")->get("fenlei","index");
		 
			$this->smarty->display($tpl);
			
		}
		
		public function onList(){
			$where=" status =1 ";
			
			$catid=get("catid","i");
			$url="/module.php?m=fenlei&a=list&catid=".$catid;
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$uid=get("userid","i");
			if($uid){
				$where.=" AND userid=".$uid;
			}
			$cat=M("mod_fenlei_category")->selectRow("catid=".$catid);
			if(empty($cat)){
				$this->goAll("分类已下架",1);
			}
			$catList=false;
			$topCat=false;
			if($cat["pid"]==0){
				$topCat=$cat;
				$catList=MM("fenlei","fenlei_category")->select(array(
					"where"=>" status=1 AND pid=".$cat["catid"],
					"order"=>" orderindex ASC"
				));
				$cids=MM("fenlei","fenlei_category")->id_family($cat["catid"]);
				$where.=" AND catid in("._implode($cids).") ";
				$typeList=MM("fenlei","fenlei_category")->parseType($cat["typedata"]);
			}else{
				$catList=MM("fenlei","fenlei_category")->select(array(
					"where"=>" status=1 AND pid=".$cat["pid"],
					"order"=>" orderindex ASC"
				));
				$topCat=M("mod_fenlei_category")->selectRow(array(
					"where"=>"catid=".$cat["pid"],
					"fields"=>"catid,title"
				));
				$where.=" AND catid=".$catid;
				$typeList=MM("fenlei","fenlei_category")->parseType($cat["typedata"]);
			}
			$seo=array(
				"title"=>$cat["title"],
				"description"=>$cat["description"]
			);
			$sc_id=get("sc_id","i");
			if($sc_id){
				$where.=" AND sc_id=".$sc_id;
			}
			//价格区间
			$sprice=get("sprice","h");
			if($sprice){
				if(strpos($sprice,"以上")>0){
					$where.=" AND money>=".intval($sprice);
				}elseif(strpos($sprice,"以下")>0){
					$where.=" AND money<=".intval($sprice);
				}else{
					$ex=explode("-",$sprice);
					$minPrice=intval($ex[0]);
					$maxPrice=intval($ex[1]);
					$where.=" AND money>=".$minPrice." AND money<".$maxPrice;
				}
			}
			//类型
			$typeid=get("typeid","i");
			if($typeid){
				$where.=" AND typeid=".$typeid;
			}
			 
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" catding DESC,updatetime DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					if($v["imgsdata"]){
						$ims=explode(",",$v["imgsdata"]);
						$imgsdata=array();
						foreach($ims as $im){
							$imgsdata[]=images_site($im);
						}
						$v["imgsdata"]=$imgsdata;
					}
					$data[$k]=$v;
				}
			} 
			//推荐帖子
			$recList=array();
			if($start==0){
				if($cat["pid"]==0){
					$where="   catid in("._implode($cids).")  AND isrecommend=1 ";
				}else{
					$where=" status=1 AND  catid=".$catid." AND isrecommend=1 ";
				}
				
				$recList=MM("fenlei","fenlei")->Dselect(array(
					"where"=>$where,
					"limit"=>6
				));
			} 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			//区域
			$scList=M("site_city")->id_list(array(
				"where"=>" status=1 "
			));
			//价格区间
			$priceList=MM("fenlei","fenlei")->priceList($cat["catid"]);
			$uniTpl=MM("fenlei","fenlei_category")->getUniTpl($catid,1);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"cat"=>$cat,
					"catList"=>$catList,
					"seo"=>$seo,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"scList"=>$scList,
					"priceList"=>$priceList,
					"typeList"=>$typeList,
					"recList"=>$recList,
					"topCat"=>$topCat,
					"uniTpl"=>$uniTpl
				)
			);
			 
			$tpl=MM("fenlei","fenlei_category")->getTpl($catid,1);
			$tpl=$tpl?$tpl:"fenlei/list.html";
			$this->smarty->display($tpl);
		}
		
		public function onUniTpl(){
			$catid=get("catid","i");
			$uniTpl=MM("fenlei","fenlei_category")->getUniTpl($catid,1);
			$this->smarty->goAssign(array(
				"uniTpl"=>$uniTpl
			));
		}
		public function onHongbao(){
			$where=" status =1 AND hb_on=1 AND hb_num>0 ";
			$url="/module.php?m=fenlei&a=hongbao";
			 
			$seo=array(
				"title"=>"红包任务推广",
				"description"=>"红包任务推广"
			);
			 
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" catding DESC,updatetime DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					if($v["imgsdata"]){
						$ims=explode(",",$v["imgsdata"]);
						$imgsdata=array();
						foreach($ims as $im){
							$imgsdata[]=images_site($im);
						}
						$v["imgsdata"]=$imgsdata;
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
					 
					"seo"=>$seo,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					 
				)
			);
		 
			$this->smarty->display("fenlei/hongbao.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$userid=M("login")->userid;
			$data=MM("fenlei","fenlei")->selectRow(array("where"=>"id=".$id));
			if(empty($data) || $data["status"]>1){
				$this->goAll("数据不存在",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$data["timeago"]=timeago(strtotime($data["createtime"]));
			if($data["imgsdata"]){
				$ims=explode(",",$data["imgsdata"]);
				$imgsdata=array();
				foreach($ims as $im){
					$imgsdata[]=images_site($im);
				}
				
			}
			$cat=MM("fenlei","fenlei_category")->selectRow("catid=".$data["catid"]);
			 
			$recList=MM("fenlei","fenlei")->Dselect(array(
				"where"=>" isrecommend=1 AND status=1 AND catid=".$data["catid"],
				"limit"=>6,
				"order"=>" catding DESC,updatetime DESC"
			));
			//处理浏览记录
			$view=M("mod_fenlei_view")->selectRow("userid=".$userid." AND objectid=".$id);
			if(!$view){
				M("mod_fenlei_view")->insert(array(
					"userid"=>$userid,
					"objectid"=>$id,
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("fenlei","fenlei")->changenum("view_num",1,"id=".$id);
			}
			//是否点赞
			$islove=0;
			$love=M("love")->selectRow("tablename='mod_fenlei' AND userid=".$userid." AND objectid=".$id);
			if($love){
				$islove=1;
			}
			//是否收藏
			$isfav=0;
			$fav=M("fav")->selectRow("tablename='mod_fenlei' AND userid=".$userid." AND objectid=".$id);
			if($fav){
				$isfav=1;
			}
			//属性
			$typeList=MM("fenlei","fenlei_category")->parseType($cat["typedata"]);
			if(isset($typeList[$data['typeid']])){
				$data["typeid_name"]=$typeList[$data['typeid']];
			}
			$sitecity=M("site_city")->selectRow("sc_id=".$data["sc_id"]);
			if($sitecity){
				$data["sc_id_name"]=$sitecity["title"];
			}else{
				$data["sc_id_name"]="暂无位置";
			}
			//联系方式
			$com=M("mod_fenlei_company")->selectRow("userid=".$data["userid"]." AND status=1 ");
			 
			if($com){
				$com["imgurl"]=images_site($com["imgurl"]);
			}
			//扩展表单
			 if($data["ex_table_data_id"]){
				 $fieldsList=M("table_data")->get($cat["ex_table_id"],$data["ex_table_data_id"]);
			 }
			//shareLink
			$shareLink=HTTP_HOST."/module.php?m=fenlei&a=show&id=".$id."&share_userid=".$userid;
			$share_userid=get("share_userid","i");
			$flConfig=M("mod_fenlei_config")->selectRow("1");
			if($share_userid && $data["hb_on"] && $flConfig["hb_on"]){
				$sendwx=true;
				if($flConfig["hb_wx_follow"]){
					$sendwx=M("weixin")->checkFollow($share_userid);
				}
				if($sendwx){
					MM("fenlei","fenlei_hongbao")->addHongBao(array(
						"userid"=>$share_userid,
						"fid"=>$id
					),$data);
				}
					
			}
			$hbList=array();
			if($data["hb_on"]){
				$hbList=MM("fenlei","fenlei_hongbao")->Dselect(array(
					"where"=>" fid=".$id,
					"order"=>"id DESC"
				));
			}
			$isadmin=0;
			$admin=M("mod_fenlei_category_admin")->selectRow("userid=".$userid." AND status=1 AND (catid=".$data["catid"]." or catid=".$cat["pid"].")" );
			if($admin){
				$isadmin=1;
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"recList"=>$recList,
				"isfav"=>$isfav,
				"islove"=>$islove,
				"userid"=>$userid,
				"imgsdata"=>$imgsdata,
				"typeList"=>$typeList,
				"cat"=>$cat,
				"com"=>$com,
				"fieldsList"=>$fieldsList,
				"shareLink"=>$shareLink,
				"hbList"=>$hbList,
				"comment_objectid"=>$id,
				"comment_tablename"=>"mod_fenlei",
				"comment_f_userid"=>$data['userid'],
				"comment_open"=>$cat["comment_open"],
				"isadmin"=>$isadmin,
			));
		 
			
			$tpl=MM("fenlei","fenlei_category")->getTpl($cat["catid"],2);
			$tpl=$tpl?$tpl:"fenlei/show.html";
			$this->smarty->display($tpl);
		}
		
		public function onSearch(){
			$where=" status=1 ";
			$keyword=get("keyword","h");
			$where.=" AND title like '%".$keyword."%' ";
			$uid=get("userid","i");
			if($uid){
				$where.=" AND userid=".$uid;
			}
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei")->select($option,$rscount);
		 
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					if($v["imgsdata"]){
						$ims=explode(",",$v["imgsdata"]);
						$imgsdata=array();
						foreach($ims as $im){
							$imgsdata[]=images_site($im);
						}
						$v["imgsdata"]=$imgsdata;
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
					"keyword"=>$keyword, 
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("fenlei/search.html");
		}
		
		public function onUser(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid,"userid,user_head,grade,nickname,money,gold");
			$rank=M("user_rank")->getLevel($user["grade"]);
			$invitecode=M("user_invitecode")->getCode($userid);
			$reg_invitecode=0;
			if(defined("REG_INVITECODE") && REG_INVITECODE==1){
				$reg_invitecode=1;
			}
			$topic_num=M("mod_fenlei")->selectOne(array(
				"where"=>" userid=".$userid,
				"fields"=>" count(*)  as ct"
			));
		 
			$this->smarty->goAssign(array(
				"user"=>$user,
				"topic_num"=>$topic_num,
				"rank"=>$rank,
				"invitecode"=>$invitecode,
				"reg_invitecode"=>$reg_invitecode,
			));
			$this->smarty->display("fenlei/user.html");
		}
		
		public function onMyfav(){
			M("login")->checkLogin();
			$this->smarty->display("fenlei/myfav.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=fenlei&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			//红包
			$hbList=M("mod_fenlei_hbconfig")->select(array(
				"where"=>" status=1",
				"order"=>"hb_money DESC"
			));
			$flConfig=M("mod_fenlei_config")->selectRow("1");
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"hbList"=>$hbList,
					"flConfig"=>$flConfig
				)
			);
			$this->smarty->display("fenlei/my.html");
		}
		public function onAdd(){
			
			$id=get_post("id","i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid,"userid,nickname,user_head,grade");
	 
			M("blacklist_post")->check($userid); 
			M("user")->canPost($userid);
			$black=M("mod_fenlei_black")->selectRow("userid=".$userid);
			if($black){
				$this->goAll("由于".$black["content"]."您被禁止发布",1);
			}
			$catid=get("catid","i");
			$nickname=$telephone=$address="";
			$com=M("mod_fenlei_company")->selectRow("userid=".$userid);
			if($id){
				$data=M("mod_fenlei")->selectRow(array("where"=>"id=".$id));
				$catid=$data["catid"];
				$data["imgurl"]=images_site($data['imgurl']);
				if($data["imgsdata"]){
						$imgs=explode(",",$data["imgsdata"]);
						foreach($imgs as $v){
							$imgsdata[]=array(
								"imgurl"=>$v,
								"trueimgurl"=>images_site($v)
							);
						}
				}
				$nickname=$data["nickname"];
				$telephone=$data["telephone"];
				$address=$data["address"];
			}elseif($com){
				$nickname=$com["nickname"];
				$telephone=$com["telephone"];
				$address=$com["address"];
			}else{
				$addr=M("user_lastaddr")->get($userid);
				$nickname=$addr["nickname"];
				$telephone=$addr["telephone"];
				$address=$addr["address"];
			}
			
			$payMoney=0;
			$cat=M("mod_fenlei_category")->selectRow("catid=".$catid);
			if($cat){
				
				$pcat=M("mod_fenlei_category")->selectRow("catid=".$cat["pid"]);
				if($cat["money"]!=0){				 
					$payMoney=$cat["money"];
				}elseif($cat["pid"]){
									 
					$payMoney=$pcat["money"];
				}
				$typeList=MM("fenlei","fenlei_category")->parseType($cat["typedata"]);
			}
			
			$scList=M("site_city")->id_list(array(
				"where"=>" status=1 "
			));
			$catList=MM("fenlei","fenlei_category")->where("pid=?")->order("orderindex ASC")->all($catid);
			//增加扩展表
			$tableid=$cat["ex_table_id"];
			$fieldsList=array();
			if($tableid){
				if($data["ex_table_data_id"]){
					$fieldsList=M("table_data")->get($tableid,$data["ex_table_data_id"]);
				}else{
					$fieldsList=M("table_fields")->select(array(
						"where"=>"tableid=".$tableid,
						"order"=>"orderindex ASC"
					));
				}
			}
			//等级折扣
			$config=M("mod_fenlei_config")->selectRow("1");
			if($payMoney && $config["rank_on"]==1){
				$rank=M("user_rank")->getLevel($user["grade"]);
				$payMoney=(100-$rank["discount"])*0.01*$payMoney;
			}
			
			
			$this->smarty->goassign(array(
				"data"=>$data,
				"catid"=>$catid,
				"cat"=>$cat,
				"catList"=>$catList,
				"imgsdata"=>$imgsdata,
				"payMoney"=>$payMoney,
				"scList"=>$scList,
				"typeList"=>$typeList,
				"nickname"=>$nickname,
				"telephone"=>$telephone,
				"address"=>$address,
				"fieldsList"=>$fieldsList,
				"inIos"=>$this->ios()
			));
			$this->smarty->display("fenlei/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			M("blacklist")->check($userid);
			M("blacklist_post")->check($userid);
			M("user")->canPost($userid);
			$black=M("mod_fenlei_black")->selectRow("userid=".$userid);
			if($black){
				$this->goAll("由于".$black["content"]."您被禁止发布",1);
			}
			$id=get_post("id","i");
			
			$data=M("mod_fenlei")->postData();
			if(empty($data["title"])){
				$this->goAll("标题不能为空",1);
			}
			$data["userid"]=$userid;
			$data["updatetime"]=date("Y-m-d H:i:s");
			$data["imgsdata"]=safeImgsData($data["imgsdata"]);
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				$data["imgurl"]=$ims[0];
			} 
			$cat=M("mod_fenlei_category")->selectRow("catid=".$data["catid"]);
			$pcat=M("mod_fenlei_category")->selectRow("catid=".$cat["pid"]);
			//处理联系方式
			M("user_lastaddr")->add(array(
				"nickname"=>$data["nickname"],
				"telephone"=>$data["telephone"],
				"address"=>$data["address"]
			),$userid);
			if($id){
				$action="finish";
				$row=M("mod_fenlei")->selectRow("id=".$id);
				$data["ex_table_data_id"]=M("table_data")->saveTable($cat["ex_table_id"],$row["ex_table_data_id"]);
				M("mod_fenlei")->update($data,"id='$id'");
			}else{
				$data["createtime"]=date("Y-m-d H:i:s");
				$data["ex_table_data_id"]=M("table_data")->saveTable($cat["ex_table_id"],0);
				$action="pay";
				//设置结束天数
				$on_day=$cat["on_day"];
				if($on_day==0){
					$on_day=365;
				}
				$data["offtime"]=time()+$on_day*3600*24;
				$user=M("user")->selectRow(array(
					"fields"=>"money,grade,userid",
					"where"=>" userid=".$userid
				));
				//支付费用
				if($cat["money"]!=0){
					$payMoney=$cat["money"];
				}elseif($cat["pid"]){			 
					$pcat=M("mod_fenlei_category")->selectRow("catid=".$cat["pid"]);
					if($pcat["money"]==0){
						$action="finish";
					}
					$payMoney=$pcat["money"];
				}else{
					$action="finish";					 
				} 
				
				if( $payMoney!=0 &&  $action=="finish"){
					$data["status"]=1;
				}else{
					$data["status"]=0;
				}
				//等级折扣
				$config=M("mod_fenlei_config")->selectRow("1");
				if($payMoney && $config["rank_on"]==1){
					$rank=M("user_rank")->getLevel($user["grade"]);
					$payMoney=(100-$rank["discount"])*0.01*$payMoney;
				}
				
				$data["paymoney"]=$payMoney;
				$id=M("mod_fenlei")->insert($data);
			}
			$inIos=$this->ios();
			if($inIos){
				$action="finish";
			}
			$rdata=array(
				"action"=>$action,
				"id"=>$id
			);
			if(!$data['ispay'] && !$inIos){
				$_GET['id']=$id;
				$res=$this->onPay(1);
				$rdata['payurl']=$res['payurl'];
				$rdata['orderno']=$res['orderno'];
			}
			$this->goall("保存成功",0,$rdata);
		}
		
		public function onPay($return=0){
			M("login")->checkLogin();
			$inIos=$this->ios();
			$userid=M("login")->userid;
			$orderno="Re".M("maxid")->get();
			$id=get("id",'i');
			$ask=M("mod_fenlei")->selectRow("id=".$id);
			$money= $ask['paymoney'];
			/**判断用户的金额**/
			$user=M("user")->selectRow(array(
				"where"=>"userid=".$userid,
				"fields"=>"userid,money"
			));
			if($user["money"]>=$money){
				M("user")->addMoney(array(
					"userid"=>$userid,
					"money"=>$user["money"],
					"content"=>"您发布同城信息花了".$money."元，"
				));
				M("mod_fenlei")->update(array(
					"ispay"=>1,
					"status"=>1
				),"id=".$id);
				$redata=array(
					"payurl"=>"",
					"action"=>"finish",
					"orderno"=>"",
					"id"=>$id
					
				);
				$this->goAll("支付成功",0,$redata);
			}
			if($inIos){
				$this->goAll("IOS暂时无法支付,请联系客服",1);
			}
			$backurl="/module.php?m=fenlei&a=success&id=".$id;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_fenlei")->update(array(
						"ispay"=>1,
						"status"=>1
					),"id='.$id.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo="发布同城信息";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			$order_product="发布同城信息";
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=$orderno";
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno,
				"id"=>$id
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
		}
		
		public function onHbPay($return=0){
			$inIos=$this->ios();
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderno="Re".M("maxid")->get();
			$id=get("id",'i');
			$hbid=get("hbid","i");
			$hongbao=M("mod_fenlei_hbconfig")->selectRow("id=".$hbid);
			$fenlei=M("mod_fenlei")->selectRow("id=".$id);
			if($fenlei["hb_on"]){
				$this->goAll("已经设置红包了",1);
			}
			$money= $hongbao["hb_money"]*1.01;
			/**判断用户的金额**/
			$user=M("user")->selectRow(array(
				"where"=>"userid=".$userid,
				"fields"=>"userid,money"
			));
			if($user["money"]>=$money){
				M("user")->addMoney(array(
					"userid"=>$userid,
					"money"=>-$money,
					"content"=>"设置红包奖励花了".$money."元，"
				));
				M("mod_fenlei")->update(array(
					"hb_num"=>$hongbao["hb_num"],
					"hb_on"=>1,
					"hb_money"=>$hongbao["hb_money"]
				),"id=".$id);
				$redata=array(
					"payurl"=>"",
					"action"=>"finish",
					"orderno"=>""
				);
				$this->goAll("红包设置成功",0,$redata);
			}
			if($inIos){
				$this->goAll("IOS暂时无法支付,请联系客服",1);
			}
			$backurl="/module.php?m=fenlei&a=success&id=".$id;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_fenlei")->update(array(
						"hb_num"=>'.$hongbao["hb_num"].',
						"hb_on"=>1,
						"hb_money"=>'.$hongbao["hb_money"].'
					),"id='.$id.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo="设置红包奖励";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			$order_product="设置红包奖励";
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=$orderno";
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
		} 
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=MM("fenlei","fenlei")->selectRow("id=".$id);
			$userid=M("login")->userid;
			$cat=MM("fenlei","fenlei_category")->selectRow(array(
				"where"=>"catid=".$row["catid"],
				"fields"=>"catid,pid"
			));
			if(!$cat){
				$this->goAll("暂无权限",1);
			}
			$admin=M("mod_fenlei_category_admin")->selectRow(" userid=".$userid." AND status=1 AND catid in(".$row["catid"].",".$cat["pid"].") ");
			$isrecommend=0;
			if( $admin){
				if($row["isrecommend"]==1){
					$isrecommend=0;
				}else{
					$isrecommend=1;
				}
				MM("fenlei","fenlei")->update(array(
					"isrecommend"=>$isrecommend
				),"id=".$id);
				$this->goAll("推荐成功",0,$isrecommend);
			}else{
				$this->goAll("暂无权限",1);
			} 
		}
		public function onDelete(){
			M("login")->checkLogin();
			$id=get_post('id',"i");
			$row=M("mod_fenlei")->selectRow("id=".$id);
			$userid=M("login")->userid;
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限");
			}
			M("mod_fenlei")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		public function onOut(){
			M("login")->checkLogin();
			$id=get_post('id',"i");
			$row=M("mod_fenlei")->selectRow("id=".$id);
			$userid=M("login")->userid;
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限");
			}
			M("mod_fenlei")->update(array("status"=>2),"id=$id");
			$this->goAll("下架成功");
			 
		}
		public function onUpdateTime(){
			M("login")->checkLogin();
			$id=get_post('id',"i");
			$row=M("mod_fenlei")->selectRow("id=".$id);
			if(strtotime($row["updatetime"])>time()-3600){
				$this->goAll("距离上次刷新时间距离太多",1);
			}
			$cat=M("mod_fenlei_category")->selectRow("catid=".$row["catid"]);
			//配置
		
			$fc=M("mod_fenlei_config")->selectRow("1");
			if($cat["update_money"]){
				$money= $cat['update_money'];
			}else{
				$money= $fc['update_money'];
			}
			
			//用户金额
			$userid=M("login")->userid;
			$user=M("user")->selectRow(array(
				"where"=>"userid=".$userid,
				"fields"=>"userid,money"
			));
			if($user["money"]<$money){
				$this->goAll("余额不足",1);
			}
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>$user["money"],
				"content"=>"您更新同城信息花了".$money."元，"
			));
			$day=$cat["on_day"];
			if($day==0){
				$day=365;
			}
			M("mod_fenlei")->update(array(
				"updatetime"=>date("Y-m-d H:i:s"),
				"offtime"=>time()+$day*3600*24
			),"id=".$id);
			$this->goAll("更新成功");
		}
		
		public function ondingIndex($return=0){
			M("login")->checkLogin();
			$orderno="Re".M("maxid")->get();
			$id=get("id",'i');
			$ask=M("mod_fenlei")->selectRow("id=".$id);
			$backurl="/module.php?m=fenlei&a=success&id=".$id;
			$etime=time()+3600*24*7;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_fenlei")->update(array(
						"isindex"=>1,
						"isindex_etime"=>'.$etime.'  
					),"id='.$id.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$order_product=$orderinfo="用户置顶到分类信息首页";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			//配置
			$fc=M("mod_fenlei_config")->selectRow("1");
			$money= $fc['dingmoney'];
			if($money==0){
				$this->goAll("暂时无法置顶",1);
			}
			if($ask["isindex"]){
				$this->goAll("已经置顶了",1);
			}
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			 
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=$orderno";
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
		}
		
		public function ondingcat($return=0){
			M("login")->checkLogin();
			$orderno="Re".M("maxid")->get();
			$id=get("id",'i');
			$ask=M("mod_fenlei")->selectRow("id=".$id);
			if($ask["catding"]){
				$this->goAll("已经置顶了",1);
			}
			$backurl="/module.php?m=fenlei&a=success&id=".$id;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					M("mod_fenlei")->update(array(
						"catding"=>1
						"catding_etime"=>'.time().' 
					),"id='.$id.'");
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$order_product=$orderinfo="用户置顶到分类信息分类页";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			//分类费用
			$cat=M("mod_fenlei_category")->selectRow("catid=".$ask["catid"]);
			if($cat["dingmoney"]){
				$money= $cat["dingmoney"];
			}elseif($cat["pid"]){
				$cat=M("mod_fenlei_category")->selectRow("catid=".$cat["pid"]);
				$money= $cat["dingmoney"];
			}
			if($money==0){
				$this->goAll("暂时无法置顶",1);
			}
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			 
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=$orderno";
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
		}
		
		public function onSuccess(){
			$id=get_post("id","i");
			$data=MM("fenlei","fenlei")->selectRow(array("where"=>"id=".$id));
			if(empty($data)){
				$this->goAll("数据不存在",1);
			}
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("fenlei/success.html");
		}
		
		public function onOffLine(){
			M("mod_fenlei")->update(array(
				"status"=>2,
				
			),"offtime<".time());
			echo json_encode(array(
				"error"=>0,
				"message"=>"success"
			));
		}
		
	}
	
	
?>