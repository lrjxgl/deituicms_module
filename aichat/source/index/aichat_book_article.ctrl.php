<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_book_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=aichat_book_article&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_book_article")->select($option,$rscount);
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
			$this->smarty->display("aichat_book_article/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=aichat_book_article&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_book_article")->select($option,$rscount);
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
			$this->smarty->display("aichat_book_article/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_aichat_book_article")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_book_article/show.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$row=M("mod_aichat_book_article")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($id){
				$data=M("mod_aichat_book_article")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_book_article/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$row=M("mod_aichat_book_article")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$data=M("mod_aichat_book_article")->postData();
			if($id){
				M("mod_aichat_book_article")->update($data,"id=".$id);
			}else{
				M("mod_aichat_book_article")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$row=M("mod_aichat_book_article")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			 
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_book_article")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$row=M("mod_aichat_book_article")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_aichat_book_article")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		/**
		 * 重新生成
		 */
		public function onreanswer(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$row=M("mod_aichat_book_article")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_aichat_book_article")->update(array("create_status"=>0),"id=".$id);
			$history=str2arr($row["history"]);
			//提交队列
			$prompt=stripslashes(post("prompt"));
			$queuekey="aichat_create_text";
			$queue=new queue($queuekey);
			$finishdata=array(
				"bookid"=>$row["bookid"],
				"id"=>$id,
				"action"=>"create_book_article"
			);
			$queue->lpush(array(
				"action"=>"create_book_article",
				"prompt"=>$prompt,
				"history"=>$history,
				"finishdata"=>arr2str($finishdata)
			));
			$this->goAll("已提交任务，请稍后再看");
		}
		
	}

?>