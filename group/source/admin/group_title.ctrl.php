<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class group_titleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$url="/moduleadmin.php?m=group_title&a=default";
			if($status=get('status')){
				$where=" status=".$status;
				$url.="&status=".$status;
			}else{
				$where=" status<4 ";
			}
			if(get('isindex')){
				$where.=" AND isindex=1";
				$url.="&isindex=1";
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
			 
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$rscount>$per_page?$per_page:0;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page
				)
			);
			$this->smarty->display("group_title/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_group_title")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("group_title/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_group_title")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("group_title/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$data["status"]=1;
			$data["dateline"]=time();
			$data["gid"]=post("gid","i");
			$data["tagid"]=post("tagid","i");
			$data["userid"]=$this->login->userid;
			$data["title"]=post("title","h");
			$data["keywords"]=post("keywords","h");
			$data["description"]=post("description","h");
			$data["imgsdata"]=post("imgsdata","h");
			$data["last_time"]=post("last_time","i");
			$data["comment_num"]=post("comment_num","i");
			$data["click_num"]=post("click_num","i");
			$data["isrecommend"]=post("isrecommend","i");
			$data["isnew"]=post("isnew","i");
			$data["ishot"]=post("ishot","i");
			$data["love_num"]=post("love_num","i");
			$data["isding"]=post("isding","i");
			$data["isindex"]=post("isindex","i");
			$data["content"]=post("content","h");

			if($id){
				M("mod_group_title")->update($data,"id='$id'");
			}else{
				M("mod_group_title")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_group_title")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_group_title")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_group_title")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onIsIndex(){
			$id=get('id','i');
			$isindex=get('isindex','i');
			$row=M("mod_group_title")->selectRow("id=".$id);
			if($row["isindex"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_group_title")->update(array("isindex"=>$status),"id=$id");
			$this->goAll("操作成功",0,$status);
		}
		
		
	}

?>