<?php
	class group_commentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$id=get_post("id","i");
			$data=M("mod_group_title")->selectRow(array("where"=>"id={$id}"));
			if(empty($data)){
				$this->goAll("数据出错",1);
			}
			$gid=$data["gid"];
			 
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
						$v["timeago"]=timeago($v["dateline"]);
						$childs[$v['pid']][]=$v;
					}
				}
				foreach($cms as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$v['imgurl']=images_site($v['imgurl']);
					$v['child']=isset($childs[$v['id']])?$childs[$v['id']]:array();
					$v["timeago"]=timeago($v["dateline"]);
					$cms[$k]=$v;
				}
			}
			$this->smarty->goassign(array(
				"list"=>$data,
				"per_page"=>$per_page, 
				"list"=>$cms,
				"ssuser"=>M("login")->getUser()
			));
			
		}
		
		public function onMy(){
			$userid=M("login")->userid; 
			$where=" userid=".$userid;
			$url="/module.php?m=group_comment&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_comment")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['timeago']=timeago($v['dateline']);
					$data[$k]=$v;
				}
			} 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page; 
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"group"=>$group
				)
			);
			$this->smarty->display("group_comment/my.html");
		}
		
		public function onSave(){
			M("login")->checklogin();
			$userid=M("login")->userid;
			//M("blacklist")->check($userid);
			
			$data=M("mod_group_comment")->postData();
			$data['newsid']=post('newsid','i');
			$news=M("mod_group_title")->selectRow("id=".$data['newsid']);
			if(!$news){
				$this->goAll("数据出错",1);
			}
			$group=MM("group","group")->get($news["gid"]);
			if(!$group['isjoin']){
				$this->goAll("您还未加入，不能发布",1);
			}
				
			$data['userid']=$userid;
			$data['dateline']=time();
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
			$id=M("mod_group_comment")->insert($data);
			M("mod_group_title")->changenum("comment_num",1,"id=".$data['newsid']);
			//通知作者
			if($news['userid']!=$userid){
				M("notice")->add(array(
					"userid"=>$news['userid'],
					"content"=>"【评论】".$data['content']
				));
			}
			$num=$news['comment_num']+1;
			$this->goAll("评论成功",0,array("id"=>$id,"num"=>$num));
		}
		
		public function onDelete(){
			M("login")->checklogin();
			$id=get('id','i');
			$row=M("mod_group_comment")->selectRow("id=".$id);
			$userid=M("login")->userid;
			$news=M("mod_group_title")->selectRow("id=".$row['newsid']);
			if($row['userid']!=$userid && $news['userid']!=$userid){
				$this->goAll("暂无权限",1);
			}else{
				M("mod_group_title")->changenum("comment_num",-1,"id=".$news['id']);
				M("mod_group_comment")->delete("id=".$id);
					$this->goAll("删除成功");
			}
		}
		
		public function onAdmin(){
			$gid=get_post("gid","i");
 
			$group=MM("group","group")->get($gid);
			if(!$group['isadmin']){
					$this->goAll("暂无权限",1);
			}
			$where=" gid=".$gid;
			$url="/module.php?m=group_comment&a=admin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_comment")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v['timeago']=timeago($v['dateline']);
					$data[$k]=$v;
				}
			} 
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
					"group"=>$group
				)
			);
			$this->smarty->display("group_comment/admin.html");
			
		}
		
	}
?>