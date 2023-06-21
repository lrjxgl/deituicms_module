<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jdo2o_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="  shopid=".SHOPID." AND  status=1 ";
			$url="/module.php?m=jdo2o_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jdo2o_place")->select($option,$rscount);
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
			$this->smarty->display("jdo2o_place/index.html");
		}
		
		public function onList(){
			$where="  shopid=".SHOPID." AND  status=1 ";
			$url="/module.php?m=jdo2o_place&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jdo2o_place")->select($option,$rscount);
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
			$this->smarty->display("jdo2o_place/index.html");
		}
		
		public function onShow(){
			$placeid=get_post("placeid","i");
			$data=M("mod_jdo2o_place")->selectRow(array("where"=>"placeid=".$placeid." AND status=1 "));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$data["mp4url"]=images_site($data["mp4url"]);
			$shop=MM("jdo2o","jdo2o_shop")->get($data["shopid"],"shopid,shopname,followed_num,place_num,imgurl");
			$userid=M("login")->userid;
			//图集
			$imgslist=array();
			if($data['imgsdata']){
				$imgs=explode(",",$data['imgsdata']);
				foreach($imgs as $img){
					$imgslist[]=images_site($img);
				}			  
			}
			//是否点赞
			$islove=0;
			$love=M("love")->selectRow("tablename='mod_jdo2o_place' AND userid=".$userid." AND objectid=".$placeid);
			if($love){
				$islove=1;
			}
			//是否收藏
			$isfav=0;
			if($userid){
				$fav=M("fav")->selectRow("tablename='mod_jdo2o_place' AND userid=".$userid." AND objectid=".$placeid);
				if($fav){
					$isfav=1;
				}
			}
			//全景图
			$vr3d=M("mod_jdo2o_place_vr3d")->selectRow("placeid=".$placeid." AND status=1");
			if($vr3d){
				$is3d=1;
			}else{
				$is3d=0;
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"comment_objectid"=>$placeid,
				"imgslist"=>$imgslist,
				"islove"=>$islove,
				"isfav"=>$isfav,
				"shop"=>$shop,
				"is3d"=>$is3d,
				"comment_tablename"=>"mod_jdo2o_place",
				"comment_f_userid"=>$data['userid'],
			));
			$this->smarty->display("jdo2o_place/show.html");
		}
		
	}

?>