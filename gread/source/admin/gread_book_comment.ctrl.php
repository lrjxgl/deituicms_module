<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_book_commentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=gread_book_comment&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_book_comment")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gread_book_comment/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_gread_book_comment")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_book_comment/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_gread_book_comment")->postData();
			if($id){
				M("mod_gread_book_comment")->update($data,"id='$id'");
			}else{
				M("mod_gread_book_comment")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_gread_book_comment")->update(array("bstatus"=>$bstatus),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_gread_book_comment")->update(array("bstatus"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>