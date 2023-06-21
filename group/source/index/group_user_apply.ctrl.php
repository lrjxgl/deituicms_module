<?php
	class group_user_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onInit(){
			M("login")->checkLogin();
		}
		
		public function onDefault(){
			
		}
		public function onAdmin(){
			$gid=get_post("gid","i");
			$group=MM("group","group")->get($gid);
			if(!$group['isadmin']){
				$this->goAll("您无权限",1);
			}
			$data=M("mod_group_user_apply")->select(array(
				"where"=>" gid=".$gid
			));
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as &$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=$us[$v['userid']]['user_head'];
				}
			}
			 
			$this->smarty->goAssign(array(
				"list"=>$data,
				"group"=>$group
			));
			$this->smarty->display("group_user_apply/admin.html");
		}
		
		
		public function onApply(){
			$gid=get_post("gid","i");
			$userid=M("login")->userid;
			$group=MM("group","group")->get($gid);
			 
			//处理是否已是会员
			$gu=M("mod_group_user")->selectRow(array(
				"where"=>"userid=".$userid." AND gid=".$gid
			));
			
		 	if(!empty($gu)){
		 		$this->goAll("你已经加入了",1);
		 	}
			$row=M("mod_group_user_apply")->selectRow(array(
				"where"=>"userid=".$userid." AND gid=".$gid
			));
			if($row){
				$this->goAll("你已经提交申请了",1);
			}
			$this->smarty->goAssign(array(
				"group"=>$group
			));
			$this->smarty->display("group_user_apply/apply.html");
		}
		
		public function onApplySave(){
			$gid=get_post('gid','i');
			$userid=M("login")->userid;
			$content=post('content','h');
			if(empty($content)){
				$this->goAll("请填写申请理由",1);
			}
			$group=M("mod_group")->selectRow(array("where"=>"gid={$gid}"));
			if(!$group){
				$this->goAll("数据出错",1);
			}
			//处理是否已是会员
			$gu=M("mod_group_user")->selectRow(array(
				"where"=>"userid=".$userid." AND gid=".$gid
			));
			
		 	if(!empty($gu)){
		 		$this->goAll("你已经加入了",1);
		 	}
			$row=M("mod_group_user_apply")->selectRow(array(
				"where"=>"userid=".$userid." AND gid=".$gid
			));
			if($row){
				$this->goAll("你已经提交申请了",1);
			}
			M("mod_group_user_apply")->insert(array(
				"gid"=>$gid,
				"userid"=>$userid,
				"daytime"=>date("Y-m-d H:i:s"),
				"content"=>$content
			));
			//发送通知
			$user=M("login")->getUser();
			$adminlist=M("mod_group_user")->select(array(
				"where"=>" gid=".$gid." AND isadmin=1 "
			));
			if($adminlist){
				foreach($adminlist as $v){
					M("notice")->add(array(
						"userid"=>$v['userid'],
						"content"=>$user['nickname']."申请加入《".$group['gname']."》，快去审核下吧"
					));
				}
			}
			$this->goAll("申请成功，请等待审核");
		}
		
		
		public function onYes(){
			$gid=get_post('gid','i');
			$group=M("mod_group")->selectRow(array("where"=>"gid={$gid}"));
			if(!$group){
				$this->goAll("数据出错",1);
			}
			$id=get_post('id','i');
			$row=M("mod_group_user_apply")->selectRow("id=".$id);
			if(!M("mod_group_user")->selectRow("gid=".$row['gid']." AND userid=".$row['userid'])){
				M("mod_group_user")->insert(array(
					"gid"=>$row['gid'],
					"userid"=>$row['userid'],
					"dateline"=>time(),
					"status"=>1
				));
				//增加会员数
				M("mod_group")->update(array(
					"user_num"=>$group['user_num']+1,
					"grade"=>$group['grade']+10
				),"gid=".$gid);
			}
			M("mod_group_user_apply")->delete("id=".$id);
			//发送通知
			M("notice")->add(array(
				"userid"=>$row['userid'],
				"content"=>"您申请加入《".$group['gname']."》，审核通过了，快去看看吧"
			));
			$this->goAll("处理成功");
		}
		
		public function onNo(){
			$gid=get_post('gid','i');
			$id=get_post('id','i');
			$group=M("mod_group")->selectRow(array("where"=>"gid={$gid}"));
			if(!$group){
				$this->goAll("数据出错",1);
			}
			M("mod_group_user_apply")->delete("id=".$id);
			//发送通知
			M("notice")->add(array(
				"userid"=>$row['userid'],
				"content"=>"您申请加入《".$group['gname']."》，没通过审核"
			));
			$this->goAll("处理成功");
		}
		
	}
?>