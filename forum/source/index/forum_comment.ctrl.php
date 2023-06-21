<?php 
class forum_commentControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$objectid=get("objectid","i");
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=24;
		$row=MM("forum","forum")->selectRow("id=".$objectid);
		//版主
		$isadmin=0;
		$admin=M("mod_forum_group_admin")->selectRow("userid=".$userid." AND status=1 AND gid=".$row["gid"]);
		if($admin){
			$isadmin=1;
		}
		$where=" status in(0,1) AND objectid=".$objectid." AND pid=0";
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id ASC"
		);
		$rscount=true;
		$data=MM("forum","forum_comment")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$uids[]=$v['userid'];
				$pids[]=$v['id'];
			}
			//下级评论
			$cds=MM("forum","forum_comment")->select(array(
				"where"=> " pid in("._implode($pids).") "
			));
			if(!empty($cds)){
				foreach($cds as $v){
					$uids[]=$v['userid'];
				}
			}
			$cmchild=array();
			$us=M("user")->getUserByIds($uids);
			if(!empty($cds)){
				foreach($cds as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$v['timeago']=timeago(strtotime($v['createtime']));
					
					$cmchild[$v['pid']][]=$v;
				}
			}
			foreach($data as $k=>$v){
				$v['child']=$cmchild[$v['id']];
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=images_site($us[$v['userid']]['user_head']);
				$v['timeago']=timeago(strtotime($v['createtime']));
				$v['imgslist']=array();
				if($v['imgsdata']!=""){
					$imgsdata=explode(",",$v['imgsdata']);
					$imgs=array();
					foreach($imgsdata as $img){
						if(!empty($img)){
							$imgs[]=htmlspecialchars($img);
						}
					}
					if(!empty($imgs)){
						$v['imgslist']=$imgs;
					}
				}
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"isadmin"=>$isadmin,
			"list"=>$data,
			"rscount"=>$rscount,
			"per_page"=>$per_page,
			"comment_object_id"=>$objectid,
			"comment_tablename"=>"forum",
			"comment_f_userid"=>$row['userid'],
		));
		
		$this->smarty->display("comment/index.html");
	}
	
	public function onMy(){
		
		$this->smarty->display("forum_comment/my.html");
	}
	
	public function onSave(){
		M("login")->checkLogin();
		$noticeType=get_post("noticeType","h");
		if(!$noticeType){
			$noticeType="user";
		}
		$userid=M("login")->userid;
		M("blacklist")->check($userid);
		M("blacklist_post")->check($userid);
		M("user")->canPost($userid,"comment");
		$id=get_post("id","i");
		$data=M("mod_forum_comment")->postData();
		if(empty($data["content"])){
			$this->goAll("内容不能为空",1);
		}
		$forum=M("mod_forum")->selectRow(" status in(0,1) AND id=".$data["objectid"]);
		 
		if(empty($forum)){
			$this->goAll("暂时不能评价",1);
		}
		$data["gid"]=$forum["gid"];
		$rootPath="/module.php";
		if($id){
			
			$row=M("mod_forum_comment")->selectRow("id=".$id);
			
			if($row['userid']!=$userid){
				$this->goAll("您无权限",1);
			}
			M("mod_forum_comment")->update($data,"id=".$id);
		}else{
			$data['userid']=$userid;
			 
			M("mod_forum")->changenum("comment_num",1,"id=".$data['objectid']);
			$data['ip']=ip();
			$data['ip_city']=ipcity($data['ip'],1);
		 
			if(empty($data['ip_city'])){
				$data['ip_city']="本地";
			}
			M("mod_forum_group")->changenum("comment_num",1,"gid=".$forum['gid']);
			$data['createtime']=date("Y-m-d H:i:s");
			$user=M("user")->getUser($userid);
			if($userid!=$forum["userid"]){
				M("notice")->add(array(
					"content"=>$user["nickname"]."评论了你：".$data['content'],
					"userid"=>intval($forum['userid']),
					"template_id"=>"comment",
					"linkurl"=>array(
						"path"=>$rootPath,
						"m"=>"forum",
						"a"=>"show",
						"param"=>"&id=".$data['objectid']
					)
				));
			}
			
			
			M("mod_forum_comment")->insert($data);
		}
		//通知网站
		M("site_msg")->add([
			"tablename"=>"forum_comment",
			"content"=>"有人在论坛发布评论了"
		]);
		$this->goAll("保存成功");
	}
	
	public function onDelete(){
		$id=get_post('id',"i");
		$row=MM("forum","forum_comment")->selectRow("id=".$id);
		$userid=M("login")->userid;
		$admin=M("mod_forum_group_admin")->selectRow("gid=".$row["gid"]." AND userid=".$userid);
		if($row["userid"]==$userid || $admin){
			MM("forum","forum_comment")->del($row);
			$this->goAll("删除成功");
			
		}else{
			$this->goAll("暂无权限",1);
		} 
	}
	
}