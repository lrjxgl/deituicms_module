<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class book_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3,4) ";
			$url="/moduleadmin.php?m=book_article";
			$limit=48;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("book","book_article")->Dselect($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("book_article/index.html");
		}
		public function onSearch(){
			$keyword=get("keyword","h");
			$id=get_post("id","i");
			$list=MM("book","book_article")->Dselect(array(
				"where"=>" title like '%".$keyword."%' "
			));
			if($list){
				foreach($list as $k=>$v){
					if($v["id"]==$id){
						unset($list[$k]);
					}
					
				}
			}
			$this->goAll("success",0,$list);
		}
		
		public function onImport(){
			$id=get_post("id","i");
			$importid=get_post("importid","i");
			if($id==$importid){
				$this->goAll("不能导入自己的文章",1);
			}
			$art=M("mod_book_article")->selectRow("id=".$id);
			if(empty($art)){
				$this->goAll("error",1);
			}
			
			$imp=M("mod_book_article")->selectRow("id=".$importid);
			$impdata=M("mod_book_article_data")->selectRow("id=".$importid);
		 
			if($imp){
				
				M("mod_book_article")->update(array(
					"mp3url"=>$imp["mp3url"],
					"mp4url"=>$imp["mp4url"],
					"description"=>$imp["description"],
					"status"=>1
					 
				),"id=".$id);
				M("mod_book_article_data")->update(array(
					"content"=>$impdata["content"]
				),"id=".$id);
			}
			$this->goAll("导入成功");
		}
		
		public function onAdd(){
			$bookid=get_post("bookid","i");
			$id=get_post("id","i");
			$pid=get_post("pid","i");
			if($id){
				$data=M("mod_book_article")->selectRow(array("where"=>"id={$id}"));
				$data['content']=M("mod_book_article_data")->selectOne(array(				 
					"fields"=>"content",
					"where"=>"id=".$id
				));
				$bookid=$data["bookid"];
				$pid=$data["pid"];
			}
			 
			$this->smarty->goassign(array(
				"data"=>$data,
				"bookid"=>$bookid,
				"pid"=>$pid
			));
			$this->smarty->display("book_article/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$content=post('content','x');
			$data=M("mod_book_article")->postData();
			$data["status"]=1;
			if($id){
				M("mod_book_article")->update($data,"id='$id'");
				$data['id']=$id;
				$data['content']=$content;
				M("mod_book_article_log")->insert($data);
				M("mod_book_article_data")->update(array(
					"content"=>$content
				),"id=".$id);
			}else{
				$data['createtime']=date("Y-m-d H:i:s");
				$id=M("mod_book_article")->insert($data);
				$data['id']=$id;
				$data['content']=$content;
				M("mod_book_article_log")->insert($data);
				M("mod_book_article_data")->insert(array(
					"id"=>$id,
					"content"=>$content
				));
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_book_article")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_book_article")->selectRow("id=".$id);
			$child=M("mod_book_article")->selectOne(array(
				"where"=>"pid=".$id." AND status in(0,1) ",
				"fields"=>"count(*)"
			));
			if(!empty($child)){
				$this->goAll("请先删除下级内容",1);
			}
			M("mod_book_article")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onDoPageindex(){
			$id=get_post('id',"i");
			$pageindex=get("pageindex","i");
			M("mod_book_article")->update(array("pageindex"=>$pageindex),"id=$id");
			$this->goAll("处理成功");
		}
		
		
	}

?>