<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class forum_group_adminControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$gid=get("gid","i");
			$group=M("mod_forum_group")->selectRow(array(
				"where"=>"gid=".$gid,
				"fields"=>"gid,title"
			));
			$where=" status=1 AND gid=".$gid;
			$url="/moduleadmin.php?m=forum_group_admin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_forum_group_admin")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"group"=>$group,
					"url"=>$url
				)
			);
			$this->smarty->display("forum_group_admin/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_forum_group_admin")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("forum_group_admin/add.html");
		}
		
		public function onSave(){
			$gid=get_post("gid","i");
			$nickname=post("nickname","h");
			$user=M("user")->selectRow("nickname='".$nickname."' ");
			if(empty($user)){
				$this->goAll("用户不存在",1);
			}
			$userid=$user["userid"];
			$row=M("mod_forum_group_admin")->selectRow("gid=".$gid." AND userid=".$userid);
			if($row){
				$this->goAll("已经是版主了",1);
			}
			M("mod_forum_group_admin")->insert(array(
				"status"=>1,
				"gid"=>$gid,
				"userid"=>$userid,
				"dateline"=>time()
			));
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_forum_group_admin")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_forum_group_admin")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>