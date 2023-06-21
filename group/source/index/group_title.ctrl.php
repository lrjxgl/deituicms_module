<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class group_titleControl extends skymvc{
		public $group;
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			
		}
		
		public function onDefault(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			$where=" status=1 AND gid=".$gid;
			$url="/module.php?m=group_title&a=default";
			$tagid=get('tagid','i');
			if($tagid){
				$where.=" AND tagid=".$tagid;
				$url.="&tagid=".$tagid;
			}
			if(get('isrecommend')){
				$where.=" AND isrecommend=1";
				$url.="&isrecommend=1";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=MM("group","group_title")->select($option,$rscount);
	 		$taglist=MM("group","group_tags")->getList($gid);
			 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$seo=array(
		 		"title"=>$group['gname']."的主题列表"
		 	);
		 	$per_page=$start+$limit;
		 	$per_page=$rscount<$per_page?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"group"=>$group,
					"taglist"=>$taglist,
					"seo"=>$seo,
					"per_page"=>$per_page
				)
			);
			$this->smarty->display("group_title/index.html");
		}
		
	 
		
		
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_group_title")->selectRow(array("where"=>"id={$id}"));
			if($data ){
				$data['timeago']=timeago($data['dateline']);
				$user=M("user")->selectRow(array(
						"where"=>"userid=".$data['userid'],
						"fields"=>"userid,nickname,user_head"
					)
				);
				$data['nickname']=$user['nickname'];
				$data['user_head']=images_site($user['user_head']);
				if(!empty($data['imgsdata'])){
					$imgs=explode(",",$data['imgsdata']);
					foreach($imgs as $im){
						$imgsdata[]=images_site($im);
					}
					$data['imgsdata']=$imgsdata;
					
				}
				$data['zans']=MM("group","group_title_love")->getListByTopicId($data['id'],24);
			 			
			}
			$gid=$data["gid"];
			$group=MM("group","group")->get($gid);
			
			$seo=array(
		 		"title"=>strip_tags($data['content'])
		 	);
		 	/****获取评论******/
		 	$start=get('per_page','i');
		 	$limit=24;
		 	$rscount=true;
		 	$cms=MM("group","group_comment")->select(array(
				"where"=>"pid=0 AND newsid=".$id,
				"order"=>"id ASC",
				"limit"=>$limit,
				"start"=>$start
			),$rscount);
			if($cms){
				foreach($cms as $v){
					$uids[]=$v['userid'];
					$ids[]=$v['id'];
				}
				$cmschild=MM("group","group_comment")->select(array(
					"where"=>"pid in("._implode($ids).") ",
					"order"=>"id ASC",
				));
				if($cmschild){
					foreach($cmschild as $v){
						$uids[]=$v['userid'];
					}
				}
				$us=M("user")->getUserByIds($uids);
				$childs=array();
				if($cmschild){
				
					foreach($cmschild as $v){
						$v['nickname']=$us[$v['userid']]['nickname'];
						$v['user_head']=images_site($us[$v['userid']]['user_head']);
						$v['imgurl']=images_site($v['imgurl']);
						$childs[$v['pid']][]=$v;
					}
				}
				foreach($cms as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$v['imgurl']=images_site($v['imgurl']);
					$v['child']=isset($childs[$v['id']])?$childs[$v['id']]:array();
					$cms[$k]=$v;
				}
			}
			//浏览
			M("mod_group_title")->changenum("click_num",1,"id=".$id);
			$this->smarty->goassign(array(
				"data"=>$data,
				"group"=>$group,
				"seo"=>$seo,
				"cms"=>$cms,
				"ssuser"=>M("login")->getUser()
			));
			$this->smarty->display("group_title/show.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$gid=get_post("gid","i");
			$imgsdata=array();
			$group=[];
			$groupList=[];
			$id=get_post("id","i");
			if($id){
				$data=M("mod_group_title")->selectRow(array("where"=>"id={$id}"));
				
				if($data["imgsdata"]){
						$imgs=explode(",",$data["imgsdata"]);
						foreach($imgs as $v){
							$imgsdata[]=array(
								"imgurl"=>$v,
								"trueimgurl"=>images_site($v)
							);
						}
				}
				$gid=$data["gid"];
			}
			if($gid){
				$group=MM("group","group")->get($gid);
				if(!$group['isjoin']){
					$this->goAll("您还未加入，不能发布",1);
				}
				
				
			}else{
				 
				$gus=M("mod_group_user")->select(array(
					"where"=>" userid=".$userid
				));
				if($gus){
					foreach($gus as $v){
						$gids[]=$v['gid'];
						$gs[$v['gid']]=$v;
					}
					$groupList=MM("group","group")->select(array(
						"where"=>" gid in("._implode($gids).")"
					)); 
				}
			}
			 
			
			 
			$taglist=MM("group","group_tags")->getList($gid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
				"group"=>$group,
				"taglist"=>$taglist,
				"groupList"=>$groupList
			));
			$this->smarty->display("group_title/add.html");
		}
		public function onGettag(){
			$gid=get("gid","i");
			$taglist=MM("group","group_tags")->getList($gid);
			$this->goAll("success",0,array(
				"list"=>$taglist
			));
		}
		public function onSave(){
			$userid=M("login")->userid;
			$id=get_post("id","i");
			M("blacklist")->check($userid);
			$data["dateline"]=time();
			$data["gid"]=$gid=post("gid","i");
			if($gid){
				$group=MM("group","group")->get($data["gid"]);
				if(!$group['isjoin']){
					$this->goAll("您还未加入，不能发布",1);
				}
			}
			
			$data["tagid"]=post("tagid","i");
			$data["userid"]=$userid;
			$data["title"]=post("title","h");
			$data["keywords"]=post("keywords","h");
			$data["description"]=post("description","h");
			
			$data["last_time"]=time();
			$data["content"]=post("content","x");
			if(empty($data["title"])){
				$data["title"]=cutstr(strip_tags($data["content"]),128,"");
			}
			if(empty($data["description"])){
				$data["description"]=cutstr(strip_tags($data["content"]),128,"");
			}
			$data["tags"]=post("tags","h");
			//处理imgsdata
			$data['imgsdata']=post('imgsdata','h');
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){
					$data["imgurl"]=$imgsdata[0];
					$data["imgsdata"]=implode(",",$imgsdata);
				}
				
			} 
			//处理 open_data
			$open_data=post("open_data","h");
			if(!empty($open_data)){
				$ex=explode(":",$open_data);
				$open_data=sql($ex[0]).":".intval($ex[1]);
			}
			$data["open_data"]=$open_data;
			$data['tagid']=post('tagid','i');
			$data['videourl']=post('videourl','x');
			if($id){
				$row=M("mod_group_title")->selectRow("id=".$id);
				if($row['userid']!=$userid && !$group['isadmin']){
					$this->goAll("您无权限",1);
				}
				M("mod_group_title")->update($data,"id='$id'");
			}else{
				$data['status']=1;
				$id=M("mod_group_title")->insert($data);
				//订阅
				M("feeds")->add($userid,"mod_group_title",$id);
				//增加主题数
				M("mod_group")->update(array(
					"topic_num"=>$group['topic_num']+1,
					"grade"=>$group['grade']+10
				),"gid=".$data['gid']);
				
				//发送通知
				$user=M("login")->getUser();
				$adminlist=M("mod_group_user")->select(array(
					"where"=>" gid=".$gid." AND isadmin=1 "
				));
				if($adminlist){
					foreach($adminlist as $v){
						M("notice")->add(array(
							"userid"=>$v['userid'],
							"content"=>$user['nickname']."在圈子《".$group['gname']."》发布新文章了，快去审核下吧",
							"linkurl"=>array(
								"path"=>"/module.php",
								"m"=>"group_title",
								"a"=>"show",
								"param"=>"&id=".$id
							)
						));
					}
				}
				
			}
			 
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$userid=M("login")->userid;
			$row=M("mod_group_title")->selectRow("id=".$id);
			$group=MM("group","group")->get($row["gid"]);
			if(!$row['userid']!=$userid && !$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			M("mod_group_title")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		public function ontogglestatus(){
			$id=get_post('id',"i");
			$row=M("mod_group_title")->selectRow("id=".$id);
			$group=MM("group","group")->get($row["gid"]);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			if($row["status"]==1){
				$status=4;
			}else{
				$status=1;
			}
			M("mod_group_title")->update(array("status"=>$status),"id=$id");
			$this->goall("操作成功",0,$status);
		}
		public function onForbid(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
		 
			$row=M("mod_group_title")->selectRow("id=".$id);
			$group=MM("group","group")->get($row["gid"]);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			M("mod_group_title")->update(array("status"=>4),"id=$id");
			$this->goall("禁止成功");
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
		 
			$row=M("mod_group_title")->selectRow("id=".$id);
			$group=MM("group","group")->get($row["gid"]);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			M("mod_group_title")->update(array("status"=>1),"id=$id");
			$this->goall("审核成功");
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_group_title")->selectRow("id=".$id);
			if($row['isrecommend']==1){
				$isrecommend=0;
			}else{
				$isrecommend=1;
			}
			 
			$group=MM("group","group")->get($row["gid"]);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			M("mod_group_title")->update(array("isrecommend"=>$isrecommend),"id=$id");
			$this->goall("状态修改成功",0,$isrecommend);
		}
		
		public function onding(){
			$id=get_post('id',"i");
			$row=M("mod_group_title")->selectRow("id=".$id);
			if($row['isding']==1){
				$isrecommend=0;
			}else{
				$isrecommend=1;
			}
			$group=MM("group","group")->get($row["gid"]);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			M("mod_group_title")->update(array("isding"=>$isrecommend),"id=$id");
			$this->goall("状态修改成功",0,$isrecommend);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_group_title")->selectRow("id=".$id);
			
			$group=MM("group","group")->get($row["gid"]);
			
			if(!$row['userid']!=$userid && !$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			M("mod_group_title")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onAddClick(){
			$id=get('id','i');
			M("mod_group_title")->changenum("click_num",1,"id=".$id);
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1,2,3,4) AND userid=".$userid;
			$url="/module.php?m=group_title&a=my";
			
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("group","group_title")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page; 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"per_page"=>$per_page,
					"rscount"=>$rscount,
					"url"=>$url,
					"group"=>$group,
					
				)
			);
			$this->smarty->display("group_title/my.html");
		}
		
		public function onAdmin(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			$where=" status<8 AND gid=".$gid;
			$url="/module.php?m=group_title&a=admin";
			if(get('isforbid')){
				$where.=" AND status=4 ";
				$url.="&isforbid=1";
			}
			if(get('isrecommend')){
				$where.=" AND isrecommend=1 ";
				$url="&isrecommend=1";
			}
			if(get('isding')){
				$where.=" AND isding=1";
				$url.="&isding=1";
			}
			
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("group","group_title")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;  
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"per_page"=>$per_page,
					"rscount"=>$rscount,
					"url"=>$url,
					"group"=>$group,
					"isadmin"=>1
				)
			);
			$this->smarty->display("group_title/admin.html");
		}
	}

?>