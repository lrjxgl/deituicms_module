<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_blogControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1)";
			$url="/module.php?m=fishing_blog&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_blog")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_blog/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1)";
			
			$placeid=get("placeid","i");
			if($placeid){
				$where.=" AND placeid=".$placeid;
			}
			$uid=get("userid","i");
			if($uid){
				$where.=" AND userid=".$uid;
			}
			$url="/module.php?m=fishing_blog&a=list";
			$limit=6;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_blog")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("fishing_blog/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_fishing_blog")->selectRow(array("where"=>"id=".$id));
			$author=M("user")->getUser($data['userid'],"userid,nickname,user_head,follow_num,followed_num");
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
			$place=M("mod_fishing_place")->selectRow("placeid=".$data["placeid"]);
			//登录用户处理
			$userid=M("login")->userid;
			//浏览记录
			$isfav=0;
			$islove=0;
			if($userid){
				//点赞
				$love=M("love")->selectRow("tablename='mod_fishing_blog' AND userid=".$userid." AND objectid=".$id);
				if($love){
					$islove=1;
				}
				//围观
				$view=M("mod_fishing_blog_view")->selectRow("userid=".$userid." AND objectid=".$id);
				if(!$view){
					M("mod_fishing_blog_view")->insert(array(
						"userid"=>$userid,
						"objectid"=>$id,
						"createtime"=>date("Y-m-d H:i:s")
					));
					MM("sblog","sblog_blog")->update(array(
						"view_num"=>$data["view_num"]+1
					),"id=".$id);
				}
				$author["isFollow"]=0;
				//关注
				$isFollow=M("follow")->selectRow(array("where"=>"t_userid=".$author["userid"]." AND userid=".$userid."   "));
				if($isFollow){
					$author["isFollow"]=1;
				}
				//收藏
				$fav=M("fav")->selectRow("tablename='mod_fishing_blog' AND userid=".$userid." AND objectid=".$id);
				if($fav){
					$isfav=1;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"comment_objectid"=>$id,
				"comment_tablename"=>"mod_fishing_blog",
				"author"=>$author,
				"imgslist"=>$imgslist,
				"isadmin"=>$isadmin,
				"islove"=>$islove,
				"isfav"=>$isfav,
				"userid"=>$userid,
				"place"=>$place
			));
			$this->smarty->display("fishing_blog/show.html");
		}
		
		
		public function onMy(){
			M("login")->userid;
			$userid=M("login")->userid;
			
			$where=" userid=".$userid." AND status in(0,1)";
			$url="/module.php?m=fishing_blog&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_blog")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("fishing_blog/my.html");
		}
		
		public function onAdd(){
			$placeid=get("placeid","i");
			$place=M("mod_fishing_place")->where("placeid=?")->row($placeid);
			if(empty($place) || $place["status"]!=1){
				//$this->goAll("钓点已下线",1);
			}
			 
			$this->smarty->goassign(array(
				 
				"place"=>$place
			));
			$this->smarty->display("fishing_blog/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$data=M("mod_fishing_blog")->postData();
			//钓点
			$place=M("mod_fishing_place")->selectRow("placeid=".$data["placeid"]);
			$placeid=post("placeid","i");
			if(!$place || $place["status"]>1){
				//$this->goAll("钓点已下线",1);
			}
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			$imgsdata=post("imgsdata","h");
			if(!empty($imgsdata)){
				$imgsdata=safeImgsData($imgsdata);
				$data["imgsdata"]=$imgsdata;
				$ex=explode(",",$imgsdata);
				 
			}
			M("mod_fishing_blog")->insert($data);
			$config=M("mod_fishing_config")->selectRow("1");
			M("mod_fishing_place")->update(array(
				"grade"=>$place["grade"]+$config["blog_post_grade"],
			),"placeid=".$placeid);
			$this->goall("保存成功");
		}
	 
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$row=M("mod_fishing_blog")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_fishing_blog")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>