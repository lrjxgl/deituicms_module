<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_bookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=aichat_book&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_book")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("aichat_book/index.html");
		}
		
		public function onAdd(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_aichat_book")->selectRow(array("where"=>"bookid=".$bookid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_book/add.html");
		}
		
		public function onSave(){
			$bookid=get_post("bookid","i");
			$data=M("mod_aichat_book")->postData();
			if($bookid){
				M("mod_aichat_book")->update($data,"bookid=".$bookid);
			}else{
				M("mod_aichat_book")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$bookid=get_post('bookid',"i");
			$row=M("mod_aichat_book")->selectRow("bookid=".$bookid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_book")->update(array("status"=>$status),"bookid=".$bookid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$bookid=get_post('bookid',"i");
			M("mod_aichat_book")->update(array("status"=>11),"bookid=".$bookid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>