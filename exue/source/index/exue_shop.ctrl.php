<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=exue_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_shop")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("exue_shop/index.html");
		}
		public function onSearch(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=exue_shop&a=search";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_shop")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
					"rscount"=>$rscount,
					"url"=>$url,
					"keyword"=>$keyword
				)
			);
			$this->smarty->display("exue_shop/search.html");
		}
		public function onList(){
			$where=" status in(0,1,2) ";
			$url="/module.php?m=exue_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$gps=gps_get();
			$lat=$gps['lat'];
			$lng=$gps['lng'];
			$type=get("type","h");
			$order=" shopid DESC";
			$fields=" * ";
			switch($type){
				 case "near":
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
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where,
				"fields"=>$fields
			);
			$rscount=true;
			
			$data=M("mod_exue_shop")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("exue_shop/index.html");
		}
		
		public function onShow(){
			 
			$shopid=get_post("shopid","i");
			$shop=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if(empty($shop) || $shop["status"]>1){
				$this->goAll("商家已下架");
			}
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$shop["isFollow"]=0;
			if($userid){
				$isFollow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shop["shopid"]);
				if($isFollow){
					$shop["isFollow"]=1;
				}
			}
			$list=MM("exue","exue_blog")->Dselect(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"order"=>"id DESC",
				"limit"=>20
			));
			$this->smarty->goassign(array(
				"shop"=>$shop,
				"list"=>$list
			));
			$this->smarty->display("exue_shop/home.html");
		}
		
		public function onHome(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if(empty($shop) || $shop["status"]>1){
				$this->goAll("商家已下架");
			}
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$shop["isFollow"]=0;
			if($userid){
				$isFollow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shop["shopid"]);
				if($isFollow){
					$shop["isFollow"]=1;
				}
			}
			 
			$this->smarty->goassign(array(
				"shop"=>$shop,
				 
			));
			$this->smarty->display("exue_shop/home.html");
		}
		public function onBlog(){
			$shopid=get_post("shopid","i");
			 
			$list=MM("exue","exue_blog")->Dselect(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"order"=>"id DESC",
				"limit"=>20
			));
			$this->smarty->goassign(array(
			 
				"list"=>$list
			));
			$this->smarty->display("exue_shop/blog.html");
		}
		public function onDetail(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if(empty($shop) || $shop["status"]>1){
				$this->goAll("商家已下架");
			}
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$shop["isFollow"]=0;
			if($userid){
				$isFollow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shop["shopid"]);
				if($isFollow){
					$shop["isFollow"]=1;
				}
			}
			$this->smarty->goassign(array(
				"shop"=>$shop
			));
			$this->smarty->display("exue_shop/detail.html");
		}
		
		public function onCourse(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if(empty($shop) || $shop["status"]>1){
				$this->goAll("商家已下架");
			}
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$shop["isFollow"]=0;
			if($userid){
				$isFollow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shop["shopid"]);
				if($isFollow){
					$shop["isFollow"]=1;
				}
			}
			$list=MM("exue","exue_course")->Dselect($ops);
			$this->smarty->goassign(array(
				"shop"=>$shop,
				"list"=>$list
			));
			$this->smarty->display("exue_shop/course.html");
		}
		
		public function onTeacher(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".$shopid));
			$userid=M("login")->userid;
			if(empty($shop) || $shop["status"]>1){
				$this->goAll("商家已下架");
			}
			$shop["imgurl"]=images_site($shop["imgurl"]);
			$shop["isFollow"]=0;
			if($userid){
				$isFollow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shop["shopid"]);
				if($isFollow){
					$shop["isFollow"]=1;
				}
			}
			$list=MM("exue","exue_teacher")->Dselect($ops);
			$this->smarty->goassign(array(
				"shop"=>$shop,
				"list"=>$list
			));
			$this->smarty->display("exue_shop/teacher.html");
		}
		
		public function onToggleFollow(){
			$shopid=get("shopid","i");
			$userid=M("login")->userid;
			$follow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shopid);
			if($follow){
				M("mod_exue_follow")->delete("userid=".$userid." AND shopid=".$shopid);
				M("mod_exue_course_feeds")->delete("userid=".$userid." AND shopid=".$shopid);
				M("mod_exue_shop")->changenum("follow_num",-1,"shopid=".$shopid);
				$isFollow=0;
			}else{
				M("mod_exue_follow")->insert(array(
					"userid"=>$userid,
					"shopid"=>$shopid,
					"dateline"=>time()
				));
				//同步数据
				$courseids=M("mod_exue_course")->selectCols(array(
					"where"=>" shopid=".$shopid." AND status=1 ",
					"fields"=>"courseid"
				));
				if($courseids){
					foreach($courseids as $courseid){
						M("mod_exue_course_feeds")->insert(array(
							"userid"=>$userid,
							"shopid"=>$shopid,
							"courseid"=>$courseid,
							"dateline"=>time()
						));
					}
				}
				$isFollow=1;
				M("mod_exue_shop")->changenum("follow_num",1,"shopid=".$shopid);
			}
			$this->goAll("success",0,array(
				"isFollow"=>$isFollow
			));
			 
		} 
		
	}

?>