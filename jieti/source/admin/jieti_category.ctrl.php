<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jieti_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$pid=get("pid","i");
			$where=" status in(0,1) AND pid=".$pid;
			
			$data=M("mod_jieti_category")->select(array(
				"where"=>$where,
				"order"=>"orderindex ASC"
			));
			if($pid){
				$cat=M("mod_jieti_category")->selectRow("catid=".$pid);
			} 
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"cat"=>$cat,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("jieti_category/index.html");
		}
		
		public function onAdd(){
			$catid=get_post("catid","i");
			if($catid){
				$data=M("mod_jieti_category")->selectRow(array("where"=>"catid={$catid}"));
				
			}
			$catlist=M("mod_jieti_category")->select(array(
				"where"=>" status in(0,1) AND pid=0 ",
				"order"=>" orderindex ASC"
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist
			));
			$this->smarty->display("jieti_category/add.html");
		}
		
		
		public function onSave(){
			$catid=get_post("catid","i");
			$data=M("mod_jieti_category")->postData();
			if($catid){
				M("mod_jieti_category")->update($data,"catid='$catid'");
			}else{
				M("mod_jieti_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onAddMore(){
			$catlist=M("mod_jieti_category")->select(array(
				"where"=>" status in(0,1) AND pid=0 ",
				"order"=>" orderindex ASC"
			));
			$this->smarty->goassign(array(
				"catlist"=>$catlist
			));
			$this->smarty->display("jieti_category/addmore.html");
		}
		
		public function onSaveMore(){
			$pid=post("pid",'i');
			$content=post("content",'h');
			$arr=explode("\r\n",$content);
			foreach($arr as $v){
				$data=array(
					"pid"=>$pid,
					"title"=>sql($v),
					 
					"status"=>1
				);
				M("mod_jieti_category")->insert($data);
			}
			$this->goAll("保存成功");
		}
		
		public function onStatus(){
			$catid=get_post('catid',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_jieti_category")->update(array("bstatus"=>$bstatus),"catid=$catid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			M("mod_jieti_category")->update(array("bstatus"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>