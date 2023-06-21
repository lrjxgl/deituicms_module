<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			 
			$url="/module.php?m=gread_article&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_article")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gread_article/index.html");
		}
		
		 
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_gread_article")->selectRow(array("where"=>"id={$id}"));
				
			}
		 
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_article/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$data["title"]=post("title","h");
			$data["description"]=post("description","h");
			
			$data["imgurl"]=post("imgurl","h");
			
			$data["isindex"]=post("isindex","i");
			$data["content"]=post("content","x");
			 
			if($id){
				M("mod_gread_article")->update($data,"id='$id'");
			}else{
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_gread_article")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_gread_article")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_gread_article")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onIsIndex(){
			$id=get_post('id',"i");
			$row=M("mod_gread_article")->selectRow("id=".$id);
			if($row["isindex"]==1){
				$status=0;
			}else{
				$status=1;
			}
			 
			M("mod_gread_article")->update(array("isindex"=>$status),"id=".$id);
			$this->goall("修改成功",0,$status);
		}
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_gread_article")->update(array("bstatus"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>