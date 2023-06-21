<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_courseControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=exue_course&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" courseid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_course")->Dselect($option,$rscount);
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
			$this->smarty->display("exue_course/index.html");
		}
		public function onList(){
			$catid=get("catid","i");
			$cat=M("mod_exue_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title"
			));
			 
			$where=" status=1 ";
			$url="/module.php?m=exue_course&a=list";
			if($catid){
				$cids=MM("exue","exue_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
				$url.="&catid=".$catid;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" courseid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_course")->Dselect($option,$rscount);
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
					"cat"=>$cat
				)
			);
			$this->smarty->display("exue_course/list.html");
		}
		public function onShow(){
			$userid=M("login")->userid;
			$courseid=get_post("courseid","i");
			$data=M("mod_exue_course")->selectRow(array("where"=>"courseid=".$courseid));
			if(empty($data)){
				$this->goAll("数据不存在",1);
			}
			if($data["status"]>2){
				$this->goAll("已经删除了",1);
			}
			$data["imgurl"]=images_site($data["imgurl"]);
			$shop=M("mod_exue_shop")->selectRow(array(
				"where"=>"shopid=".$data["shopid"],
				"fields"=>"shopid,title,address,imgurl,telephone,follow_num,description"
			));
			$shop["imgurl"]=images_site($shop["imgurl"]);
			//检测是否关注了
			$shop["isFollow"]=0;
			if($userid){
				$isFollow=M("mod_exue_follow")->selectRow("userid=".$userid." AND shopid=".$shop["shopid"]);
				if($isFollow){
					$shop["isFollow"]=1;
				}
			}
			
			
			$blogList=MM("exue","exue_blog")->Dselect();
			$tcList=MM("exue","exue_course_teacher")->Dselect(array(
				"where"=>" courseid=".$courseid
			));
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$addr=M("user_lastaddr")->get($userid);
			$ratyList=MM("exue","exue_order")->Dselect(array(
				"where"=>" israty=1 ",
				"order"=>"raty_time DESC",
				"limit"=>6
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"shop"=>$shop,
				"blogList"=>$blogList,
				"tcList"=>$tcList,
				"seo"=>$seo,
				"addr"=>$addr,
				"ratyList"=>$ratyList
			));
			$this->smarty->display("exue_course/show.html");
		}
		
		 
		
		
	}

?>