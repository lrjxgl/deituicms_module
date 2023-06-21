<?php
class sblog_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$this->smarty->display("sblog_blog/index.html");
	}
	public function onSearch(){
		$this->smarty->display("sblog_blog/search.html");
	}
	public function onList(){
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		switch($type){
			case "all":
			case "new":
				$where=" status in(0,1) ";
				break;
			case "hot":
				$where="status in(0,1) AND createtime>'".date("Y-m-d H:i:s",strtotime("-10 day"))."' ";
				$order="comment_num DESC";
				break;
			case "recommend":
				$where=" status in(0,1) AND isrecommend=1 ";
				break;
			case "follow":
				 
				$this->onFollow();
				return false;
				break;
			case "city":
				$addr=ipCity(ip());
				if(!empty($addr["city"])){
					$city=$addr["city"];
				}else{
					$city="";
				}
				$where=" status in(0,1) AND city='".sql($city)."' ";
				break;
			default:
				if($userid){
					$this->onFollow();
					return false;
				}else{
					$where="status in(0,1) AND  isrecommend=1 ";
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
		$list=MM("sblog","sblog_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$topicList=M("mod_sblog_topic")->select(array(
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
		$blogids=M("mod_sblog_feeds")->selectCols(array(
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
			"where"=>" status in(0,1) AND  id in("._implode($blogids).") ",
			"order"=>"  id DESC"
		);
		
		$list=MM("sblog","sblog_blog")->Dselect($ops);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$topicList=M("mod_sblog_topic")->select(array(
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
		$id=get("id","i");
		if($id){
			$topic=M("mod_sblog_topic")->selectRow("id=".$id);
		}else{
			$title=get("title","h");
			$topic=M("mod_sblog_topic")->selectRow("title='".$title."'");
		}
		if(empty($topic)){
			$this->goAll("话题不存在",1);
		}
		$topic["imgurl"]=images_site($topic["imgurl"]);
		//
		M("mod_sblog_topic")->update(array(
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
		$ids=M("mod_sblog_topic_index")->selectCols($ops);
		 
		if(!$ids){
			$where=" 1=2 ";
		}else{
			$where=" status=1 AND id in("._implode($ids).")";
		}
		
		$ops=array(
			"where"=>$where
		);
		$list=MM("sblog","sblog_blog")->Dselect($ops);
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
		$this->smarty->display("sblog_blog/topic.html");
	}
	public function onShow(){
		$id=get('id','i');
		$userid=M("login")->userid;
		$data=MM("sblog","sblog_blog")->selectRow("id=".$id." AND status in(0,1)");
		if(!$data ) $this->goAll("数据出错",1);
		//浏览记录
		if($userid){
			$view=M("mod_sblog_blog_view")->selectRow("userid=".$userid." AND objectid=".$id);
			if(!$view){
				M("mod_sblog_blog_view")->insert(array(
					"userid"=>$userid,
					"objectid"=>$id,
					"createtime"=>date("Y-m-d H:i:s")
				));
				MM("sblog","sblog_blog")->update(array(
					"view_num"=>$data["view_num"]+1
				),"id=".$id);
			}
		}
		$data["parsecontent"]=MM("sblog","sblog_blog")->parseContent($data["content"]);
		
		$data["timeago"]=timeago(strtotime($data["createtime"]));
		$author=M("user")->getUser($data['userid'],"userid,nickname,user_head,follow_num,followed_num");
		if(empty($author)){
			$author["userid"]=0;
		}
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
		$love=M("love")->selectRow("tablename='mod_sblog_blog' AND userid=".$userid." AND objectid=".$id);
		if($love){
			$islove=1;
		}
		//是否收藏
		$isfav=0;
		if($userid){
			$fav=M("fav")->selectRow("tablename='mod_sblog_blog' AND userid=".$userid." AND objectid=".$id);
			if($fav){
				$isfav=1;
			}
		}
		//管理员
		$isadmin=M("mod_sblog_admin")->selectOne(array(
			"where"=>" status=1 AND userid=".$userid,
			"fields"=>"userid"
		));
		$this->smarty->goAssign(array(
			"isadmin"=>$isadmin,
			"islove"=>$islove,
			"isfav"=>$isfav,
			"data"=>$data,
			"comment_objectid"=>$id,
			"comment_tablename"=>"mod_sblog_blog",
			"comment_f_userid"=>$data['userid'],
			"imgslist"=>$imgslist,
			"author"=>$author,
			"userid"=>$userid
		));
		$this->smarty->display("sblog_blog/show.html");
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
		$list=MM("sblog","sblog_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
		$this->smarty->display("sblog_blog/my.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$topicid=get("topicid","i");
		$topic=M("mod_sblog_topic")->selectRow(array(
			"where"=>"id=".$topicid,
			"fields"=>"id,title"
		));
		$this->smarty->goassign(array(
			"a"=>1,
			"topic"=>$topic,
			"topicid"=>$topicid
		));
		$this->smarty->display("sblog_blog/add.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$topicid=get_post("topicid","i");
		$data=M("mod_sblog_blog")->postData();
		$data["content"]=$content=post("content","h");
		
		$imgsdata=post("imgsdata","h");
		if($imgsdata){
			$ims=explode(",",$imgsdata);
			foreach($ims as $im){
				if(substr($im,0,6)=="attach"){
					$imgs[]=$im;
				}
			}
			if(!empty($imgs)){
				$data["imgurl"]=$imgs[0];
				$data["imgsdata"]=implode(",",$imgs);
			}	
		}
		$mp4url=post("mp4url","h");
		 
		if($mp4url!='' && substr($mp4url,0,5)=="video"){
			$data["mp4url"]=post("mp4url","h");
		}
		$addr=ipCity(ip());
		if(!empty($addr["city"])){
			$city=$addr["city"];
		}else{
			$city="";
		}
		$data["city"]=sql($city);
		 
		$data["userid"]=$userid;
		$data["createtime"]=date("Y-m-d H:i:s");
		$blogid=M("mod_sblog_blog")->insert($data);
		//解析主题
		if($topicid){
			M("mod_sblog_topic_index")->insert(array(
				"topicid"=>$topicid,
				"blogid"=>$blogid,
				"createtime"=>date("Y-m-d H:i:s")
			));
		}else{
			preg_match_all("/#(.*)#/iUs",$content,$tps);
			if(isset($tps[1])){
				foreach($tps[1] as $tp){
					$row=M("mod_sblog_topic")->selectRow("title='".sql($tp)."'");
					if(!$row){
						$topicid=M("mod_sblog_topic")->insert(array(
							"title"=>sql($tp),
							"createtime"=>date("Y-m-d H:i:s")
						));
					}else{
						$topicid=$row["id"];
					}
					
					M("mod_sblog_topic_index")->insert(array(
						"topicid"=>$topicid,
						"blogid"=>$blogid,
						"createtime"=>date("Y-m-d H:i:s")
					));
				}
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
			M("mod_sblog_feeds")->insert(array(
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
		$row=M("mod_sblog_blog")->selectRow("id=".$id);
		$admin=M("mod_sblog_admin")->selectRow("userid=".$userid." AND status=1 ");
		if($row["userid"]!=$userid && !$admin){
			$this->goAll("暂无权限",1);
		}
		M("mod_sblog_blog")->update(array("status"=>11),"id=".$id);
		//删除所有关注的
		M("mod_sblog_feeds")->delete("blogid=".$id);
		M("mod_sblog_topic_index")->delete("blogid=".$id);
		$this->goAll("删除成功");
	}
	
	public function onRecommend(){
		$id=get_post('id',"i");
		$row=MM("sblog","sblog_blog")->selectRow("id=".$id);
		$userid=M("login")->userid;
		$admin=M("mod_sblog_admin")->selectRow("userid=".$userid." AND status=1 ");
		if(!$admin){
			$this->goAll("暂无权限",1);
		}else{
			MM("sblog","sblog_blog")->update(array(
				"isrecommend"=>1
			),"id=".$id);
			$this->goAll("推荐成功");
		}
	}
	
}