<?php
class exue_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("exue_blog/index.html");
	}
	public function onSearch(){
		$this->smarty->display("exue_blog/search.html");
	}
	public function onList(){
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		switch($type){
			case "new":
				$where=" status=1 ";
				break;
			case "hot":
				$where="status=1 AND createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."' ";
				$order="comment_num DESC";
				break;
			case "recommend":
				$where=" status=1 AND isrecommend=1 ";
				break;
			default:
				if($userid){
					$this->onFollow();
					return false;
				}else{
					$where="status=1 AND  isrecommend=1 ";
				}
				
				break;
		}
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("exue","exue_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$topicList=M("mod_exue_topic")->select(array(
			"where"=>" isindex=1 AND status=1 "
		));
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"topicList"=>$topicList
		));
	}
	
	public function onFollow(){
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		$userid=M("login")->userid;
		$rscount=true;
		$blogids=M("mod_exue_feeds")->selectCols(array(
			"where"=>" userid=".$userid,
			"fields"=>"blogid",
			"order"=>" dateline DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if(!$blogids){
			$blogids=[0]; 
		} 
		$ops=array(
			"where"=>" status=1 AND  id in("._implode($blogids).") ",
			"order"=>"  id DESC"
		);
		
		$list=MM("exue","exue_blog")->Dselect($ops);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$topicList=M("mod_exue_topic")->select(array(
			"where"=>" isindex=1 AND status=1 "
		));
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"topicList"=>$topicList
		));
	}
	
	public function onTopic(){
		$start=get("per_page","i");
		$limit=24;
		$title=get("title","h");
		$topic=M("mod_exue_topic")->selectRow("title='".$title."'");
		if(empty($topic)){
			$this->goAll("无法访问",1);
		}
		$topic["imgurl"]=images_site($topic["imgurl"]);
		//
		M("mod_exue_topic")->update(array(
			"view_num"=>$topic["view_num"]+1
		),"id=".$topic["id"]);
		$where=" topicid=".$topic["id"];
		$order=" id DESC";
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit,
			"fields"=>"blogid"
		);
		$rscount=true;
		$ids=M("mod_exue_topic_index")->selectCols($ops);
		 
		if(!$ids){
			$where=" 1=2 ";
		}else{
			$where=" status=1 AND id in("._implode($ids).")";
		}
		
		$ops=array(
			"where"=>$where
		);
		$list=MM("exue","exue_blog")->Dselect($ops);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			"topic"=>$topic
		));
		$this->smarty->display("exue_blog/topic.html");
	}
	public function onShow(){
		$id=get('id','i');
		$userid=M("login")->userid;
		$data=MM("exue","exue_blog")->selectRow("id=".$id);
		if(!$data) $this->goAll("数据出错",1);
		$shop=array();
		if($data["shopid"]){
			$shop=MM("exue","exue_shop")->get($data["shopid"]);
		}
		//浏览记录
		if($userid){
			$view=M("mod_exue_blog_view")->selectRow("userid=".$userid." AND objectid=".$id);
			if(!$view){
				M("mod_exue_blog_view")->insert(array(
					"userid"=>$userid,
					"objectid"=>$id,
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("exue","exue_blog")->update(array(
					"view_num"=>$data["view_num"]+1
				),"id=".$id);
			}
		}
		$data["parsecontent"]=MM("exue","exue_blog")->parseContent($data["content"]);
		
		$data["timeago"]=timeago(strtotime($data["createtime"]));
		$author=M("user")->getUser($data['userid'],"userid,nickname,user_head,follow_num,followed_num");
		$author['user_head']=images_site($author['user_head']);
		//关注
		if($userid){
			$author["isFollow"]=0;
			$isFollow=M("follow")->selectRow(array("where"=>"t_userid=".$author["userid"]." AND userid=".$userid."   "));
			if($isFollow){
				$author["isFollow"]=1;
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
		$love=M("love")->selectRow("tablename='mod_exue_blog' AND userid=".$userid." AND objectid=".$id);
		if($love){
			$islove=1;
		}
		//是否收藏
		$isfav=0;
		if($userid){
			$fav=M("fav")->selectRow("tablename='mod_exue_blog' AND userid=".$userid." AND objectid=".$id);
			if($fav){
				$isfav=1;
			}
		}
		$this->smarty->goAssign(array(
			"islove"=>$islove,
			"isfav"=>$isfav,
			"data"=>$data,
			"comment_objectid"=>$id,
			"comment_tablename"=>"mod_exue_blog",
			"comment_f_userid"=>$data['userid'],
			"imgslist"=>$imgslist,
			"author"=>$author,
			"userid"=>$userid,
			"shop"=>$shop
		));
		$this->smarty->display("exue_blog/show.html");
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=24;
		$where=" status in(0,1,2) AND userid=".$userid;
		$ops=array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("exue","exue_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
		$this->smarty->display("exue_blog/my.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$this->smarty->assign(array(
			"a"=>1
		));
		$this->smarty->display("exue_blog/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$uteacher=M("mod_exue_user_teacher")->selectRow("userid=".$userid." AND isdefault=1");
		$data=M("mod_exue_blog")->postData();
		$data["content"]=$content=post("content","h");
		
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
		$data["status"]=1;
		$data["userid"]=$userid;
		if($uteacher){
			$data["tcid"]=$uteacher["tcid"];
			$data["shopid"]=$uteacher["shopid"];
		}
		$data["createtime"]=date("Y-m-d H:i:s");
		$blogid=M("mod_exue_blog")->insert($data);
		//解析主题
		preg_match_all("/#(.*)#/iUs",$content,$tps);
		if(isset($tps[1])){
			foreach($tps[1] as $tp){
				$row=M("mod_exue_topic")->selectRow("title='".sql($tp)."'");
				if(!$row){
					$topicid=M("mod_exue_topic")->insert(array(
						"title"=>sql($tp),
						"createtime"=>date("Y-m-d H:i:s")
					));
				}else{
					$topicid=$row["id"];
				}
				
				M("mod_exue_topic_index")->insert(array(
					"topicid"=>$topicid,
					"blogid"=>$blogid,
					"createtime"=>date("Y-m-d H:i:s")
				));
			}
		}
		//推送到订阅
		
		$us=M("follow")->selectCols(array(
			"fields"=>"userid",
			"where"=>"t_userid=".$userid,
			"limit"=>100000000
		));
	
		if(!$us) $us=array();
		$us[]=$userid;
		foreach($us as $uid){
			M("mod_exue_feeds")->insert(array(
				"userid"=>$uid,
				"blogid"=>$blogid,
				"fuserid"=>$userid,
				"dateline"=>time(),
			));
		}
		
		$this->goAll("发布成功");
	}
	
	public function onDelete(){
		 
		$userid=M("login")->userid;
		$id=get("id","i");
		$row=M("mod_exue_blog")->selectRow("id=".$id);
		if($row["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_exue_blog")->update(array("status"=>11),"id=".$id);
		//删除所有关注的
		M("mod_exue_feeds")->delete("blogid=".$id);
		M("mod_exue_topic_index")->delete("blogid=".$id);
		$this->goAll("删除成功");
	}
	
}