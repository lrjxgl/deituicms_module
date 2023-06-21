<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class groupControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			if(!in_array(get('a'),array('default','list','show','detail'))){
				M("login")->checkLogin();
			}
		}
		public function onUser(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid);
			$topic_num=M("mod_group_title")->selectOne(array(
				"where"=>"userid=".$userid." AND status in(0,1) ",
				"fields"=>"count(*)"
			));
			$comment_num=M("mod_group_comment")->selectOne(array(
				"where"=>"userid=".$userid." AND status in(0,1) ",
				"fields"=>"count(*)"
			));
			$this->smarty->goAssign(array(
				"user"=>$user,
				"topic_num"=>$topic_num,
				"comment_num"=>$comment_num
			));
			$this->smarty->display("group/user.html");
		}
		public function onDefault(){
			$where=" is_recommend=1 AND status=1 ";
			$url="/module.php?m=group&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("group","group")->select($option,$rscount);
			//处理是否加入
			$userid=M("login")->userid;
			$gids=M("mod_group_user")->selectCols(array(
				"where"=>" userid=".$userid,
				"fields"=>"gid"
			));
			if($gids && $data){
				 
				foreach($data as &$v){
					if(in_array($v["gid"],$gids)){
						$v["isjoin"]=1;
					}else{
						$v["isjoin"]=0;
					}
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$topiclist=MM("group","group_title")->select(array(
				"where"=>" status=1 AND isindex=1 ",
				"limit"=>24,
				"order"=>"id DESC"
			));
		 
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"topiclist"=>$topiclist
				)
			);
			$this->smarty->display("group/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=group&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("group","group")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("group/list.html");
		}
		
		public function onShow(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			//获取推荐
			$dinglist=MM("group","group_title")->select(array(
				"where"=>"isding=1 AND status=1 AND gid=".$gid,
				"order"=>" id DESC" 
			));
			 
			$reclist=MM("group","group_title")->select(array(
				"where"=>"isrecommend=1 AND status=1 AND gid=".$gid ,
				"order"=>" id DESC" ,
				"limit"=>6
			));
			
			$list=MM("group","group_title")->select(array(
				"where"=>" status=1 AND gid=".$gid ,
				"order"=>" id DESC" ,
				"limit"=>12
			));
		 	$seo=array(
		 		"title"=>$group['title']
		 	);
			$this->smarty->goassign(array(
				"group"=>$group,
				"dinglist"=>$dinglist,
				"reclist"=>$reclist,
				"list"=>$list,
				"seo"=>$seo,
				"ssuser"=>M("login")->getUser()
			));
			$this->smarty->display("group/show.html");
		}
		
		public function onDetail(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			$seo=array(
		 		"title"=>$group['title']
		 	);
			$this->smarty->goassign(array(
				"group"=>$group,
				"seo"=>$seo
			));
			$this->smarty->display("group/detail.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$gus=M("mod_group_user")->select(array(
				"where"=>" userid=".$userid
			));
			if($gus){
				foreach($gus as $v){
					$gids[]=$v['gid'];
					$gs[$v['gid']]=$v;
				}
				$groups=MM("group","group")->select(array(
					"where"=>" gid in("._implode($gids).")"
				));
				if($groups){
					foreach($groups as &$v){
						$v['isfound']=$gs[$v['gid']]['isfound'];
						$v['isadmin']=$gs[$v['gid']]['isadmin'];
					}
				}
			}
			$this->smarty->goAssign(array(
				"list"=>$groups
			));
			$this->smarty->display("group/my.html");
		}
		
		public function onAdd(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->selectRow("gid=".$gid);
			$group["true_glogo"]=images_site($group["glogo"]);
			$typelist=MM("group","group_type")->getList(); 
			$this->smarty->goassign(array(
 				"typelist"=>$typelist,
				"group"=>$group
			));
			$this->smarty->display("group/add.html");
		}
		
		
		public function onSave(){
			
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			if(!$group['isadmin']){
				$this->goAll("暂无权限",1);
			}
			$data["catid"]=post("catid","i");
			$data["grade"]=post("grade","i");
			$data["gname"]=post("gname","h");
			$data["glogo"]=post("glogo","h");
			$data["title"]=post("title","h");
			$data["keywords"]=post("keywords","h");
			$data["description"]=post("description","h");
			$data["banner"]=post("banner","h");
			 
			$data["content"]=post("content","x");
			M("mod_group")->update($data,"gid='$gid'");
			 
			$this->goall("保存成功");
		}
		
		
		
		public function onApply(){
			$typelist=MM("group","group_type")->getList(); 
			$this->smarty->goassign(array(
				"data"=>$data,
				"typelist"=>$typelist
			));
			$this->smarty->display("group/apply.html");
		}
		
		
		public function onApplySave(){
			
			$gid=get_post("gid","i");
			$data["catid"]=post("catid","i");
			$data["gname"]=post("gname","h");
			$data["glogo"]=post("glogo","h");
			$data["title"]=post("title","h");
			$data['catid']=post('catid','i');
			$data["description"]=post("description","h");
			$data["userid"]=M("login")->userid;
			$data["dateline"]=time();
			if(empty($data["gname"])){
				$this->goAll("请填写圈子名称",1);
			}
			 

			if($gid){
				M("mod_group_apply")->update($data,"gid='$gid'");
			}else{
				M("mod_group_apply")->insert($data);
			}
			$this->goall("保存成功");
		}
		public function onStatus(){
			$gid=get_post('gid',"i");
			$status=get_post("status","i");
			M("mod_group")->update(array("status"=>$status),"gid=".$gid);
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$gid=get_post('gid',"i");
			M("mod_group")->update(array("status"=>11),"gid=".$gid);
			$this->goAll("删除成功");
			 
		}
		
		public function onAdmin(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			$this->smarty->goassign(array(
				"group"=>$group
			));
			$this->smarty->display("group/admin.html");
		}
		
	}

?>