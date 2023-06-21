<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class olprint_bookControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=olprint_book&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" bookid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_olprint_book")->select($option,$rscount);
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
			$this->smarty->display("olprint_book/index.html");
		}
		
		public function onAdd(){
			$bookid=get_post("bookid","i");
			if($bookid){
				$data=M("mod_olprint_book")->selectRow(array("where"=>"bookid=".$bookid));
				
			}
			$catlist=MM("olprint","olprint_category")->children(0);
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist
			));
			$this->smarty->display("olprint_book/add.html");
		}
		
		public function onSave(){
			$bookid=get_post("bookid","i");
			$data=M("mod_olprint_book")->postData();
			if($bookid){
				M("mod_olprint_book")->update($data,"bookid='$bookid'");
			}else{
				M("mod_olprint_book")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$bookid=get_post('bookid',"i");
			$row=M("mod_olprint_book")->selectRow("bookid=".$bookid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_olprint_book")->update(array(
				"status"=>$status
			),"bookid=".$bookid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$bookid=get_post('bookid',"i");
			M("mod_olprint_book")->update(array("status"=>11),"bookid=".$bookid);
			$this->goAll("删除成功");
			 
		}
		
		public function onRecommend(){
			$bookid=get_post('bookid',"i");
			$row=M("mod_olprint_book")->selectRow("bookid=".$bookid);
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_olprint_book")->update(array(
				"isrecommend"=>$isrecommend
			),"bookid=".$bookid);
			$this->goAll("success",0,$isrecommend);
		}
		
		public function onindex(){
			$bookid=get_post('bookid',"i");
			$row=M("mod_olprint_book")->selectRow("bookid=".$bookid);
			$isindex=0;
			if($row["isindex"]==0){
				$isindex=1;
			}
			M("mod_olprint_book")->update(array(
				"isindex"=>$isindex
			),"bookid=".$bookid);
			$this->goAll("success",0,$isindex);
		}
		
		public function onCategory(){
			$ids=post('ids','i');
			$catid=post('catid','i');
			if(!$catid) $this->goall("请选择分类",1);
			if($ids){
				foreach($ids as $id){
					M("mod_olprint_book")->update(array("catid"=>$catid),"bookid=".$bookid);
				}
			}
			$this->goall("修改成功");
		}
		
		
	}

?>