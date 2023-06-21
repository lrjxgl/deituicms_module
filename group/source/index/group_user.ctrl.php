<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class group_userControl extends skymvc{
		public $group;
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			$gid=get_post('gid','i');
			$this->group=MM("group","group")->get($gid);
			if(!in_array(get('a'),array("default"))){
				if(!$this->group['isadmin']){
					$this->goAll("暂无权限",1);
				}
			}
			
		}
		
		public function onDefault(){
			$gid=get_post("gid","i");
			$group=$this->group;
			$where=" gid=".$gid;
			$url="/module.php?m=group_user&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_user")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids); 
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);	
					 
					$data[$k]=$v; 
				}
			}
		 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"group"=>$group
				)
			);
			$this->smarty->display("group_user/index.html");
		}
		
		public function onQuit(){
			$userid=M("login")->userid;
			$gid=get_post('gid','i');
			if($this->group['isfound']){
				$this->goAll("群主不能退群",1);
			} 
			M("mod_group_user")->delete("gid=$gid AND userid=".$userid);
			$this->goall("退群成功");
		}
		 
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$userid=get_post('userid','i');
			$gid=get_post('gid','i'); 
			if(!$this->group['isadmin']){
				$this->goAll("暂无权限",1);
			}
			M("mod_group_user")->update(array("status"=>$status),"gid=$gid AND userid=".$userid);
			$this->goall("状态修改成功");
		}
		
		public function onSetAdmin(){
			$userid=get_post('userid','i');
			$gid=get_post('gid','i');
			if(!$this->group['isfound']){
				$this->goAll("只有群主才能设置管理员",1);
			}
			$u= M("mod_group_user")->selectRow("gid=$gid AND userid=".$userid);
			if($u['isfound']){
				$this->goAll("无法设置群主的管理资格",1);
			}
			$isadmin=$u['isadmin']?0:1;
			M("mod_group_user")->update(array("isadmin"=>$isadmin),"gid=$gid AND userid=".$userid);
			$this->goall("修改成功");
		}
		
		public function onPass(){

			$userid=get_post('userid','i');
			$gid=get_post('gid','i'); 
			if(!$this->group['isadmin']){
				$this->goAll("暂无权限",1);
			}
			M("mod_group_user")->update(array("status"=>1),"gid=$gid AND userid=".$userid);
			$this->goall("取消成功");
		}
		
		public function onForbid(){

			$userid=get_post('userid','i');
			$gid=get_post('gid','i'); 
			if(!$this->group['isadmin']){
				$this->goAll("暂无权限",1);
			}
			M("mod_group_user")->update(array("status"=>4),"gid=$gid AND userid=".$userid);
			
			$this->goall("禁言成功");
		}
		
		public function onDelete(){
			$userid=get_post('userid','i');
			$gid=get_post('gid','i'); 
			if(!$this->group['isadmin']){
				$this->goAll("暂无权限",1);
			}
			M("mod_group_user")->update(array("status"=>11),"gid=$gid AND userid=".$userid);
			$this->goAll("删除成功");
			 
		}
		
		
		public function onAdmin(){
			$gid=get_post("gid","i");
			$group=$this->group;
			if(!$this->group['isadmin']){
				$this->goAll("暂无权限",1);
			}
			$where=" gid=".$gid;
			$url="/module.php?m=group_user&a=default";
			if(get('isforbid')){
				$where.=" AND status=4 ";
				$url.="&isforbid=1";
			}
			if(get('isadmin')){
				$where.=" AND isadmin=1 ";
				$url.="&isadmin=1";
			}
			$limit=48;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_user")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids); 
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);	
					 
					$data[$k]=$v; 
				}
			}
		 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"group"=>$group
				)
			);
			$this->smarty->display("group_user/admin.html");
		}
		
		
		
	}

?>