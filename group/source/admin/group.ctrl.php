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
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		
		public function onDefault(){
			$where=" status<11 ";
			$url="/moduleadmin.php?m=group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v["userid"];					
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("group/index.html");
		}
		
		public function onAdd(){
			$gid=get_post("gid","i");
			if($gid){
				$data=M("mod_group")->selectRow(array("where"=>"gid={$gid}"));
				
			}
			$typelist=MM("group","group_type")->getList(); 
			$this->smarty->goassign(array(
				"data"=>$data,
				"typelist"=>$typelist
			));
			$this->smarty->display("group/add.html");
		}
		
		public function onSave(){
			
			$gid=get_post("gid","i");
			$data["catid"]=post("catid","i");
			$data["grade"]=post("grade","i");
			$data["gname"]=post("gname","h");
			$data["glogo"]=post("glogo","h");
			$data["title"]=post("title","h");
			$data["keywords"]=post("keywords","h");
			$data["description"]=post("description","h");
			$data["userid"]=$this->login->userid;
			$data["dateline"]=time();
			$data["status"]=1;
			$data["user_num"]=post("user_num","i");
			$data["follow_num"]=post("follow_num","i");
			$data["banner"]=post("banner","h");
			$data["is_recommend"]=post("is_recommend","i");
			$data["isnew"]=post("isnew","i");
			$data["ishot"]=post("ishot","i");
			$data["topic_num"]=post("topic_num","i");
			$data["content"]=post("content","x");

			if($gid){
				M("mod_group")->update($data,"gid='$gid'");
			}else{
				M("mod_group")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$gid=get_post('gid',"i");
			$status=get_post("status","i");
			$row=M("mod_group")->selectRow("gid=".$gid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_group")->update(array("status"=>$status),"gid=$gid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onHot(){
			$gid=get('gid','i');
			$ishot=get('ishot','i');
			$row=M("mod_group")->selectRow("gid=".$gid);
			if($row["ishot"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_group")->update(array(
				"ishot"=>$status
			),"gid=".$gid);
			$this->goall("修改成功",0,$status);
		}
		
		public function onnew(){
			$gid=get('gid','i');
			$isnew=get('isnew','i');
			$row=M("mod_group")->selectRow("gid=".$gid);
			if($row["isnew"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_group")->update(array(
				"isnew"=>$status
			),"gid=".$gid);
			$this->goall("修改成功",0,$status);
		}
		
		public function onrecommend(){
			$gid=get('gid','i');
			$is_recommend=get('is_recommend','i');
			$row=M("mod_group")->selectRow("gid=".$gid);
			if($row["is_recommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_group")->update(array(
				"is_recommend"=>$status
			),"gid=".$gid);
			$this->goall("修改成功",0,$status);
		}
		public function onDelete(){
			$gid=get_post('gid',"i");
			M("mod_group")->update(array("status"=>11),"gid=$gid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>