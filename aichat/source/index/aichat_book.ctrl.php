<?php
class aichat_bookControl extends  skymvc{
	public function onDefault(){
		$list=M("mod_aichat_book")->select(array(
			"where"=>" status=1  ",
			"order"=>"bookid DESC",
			"limit"=>24
		));
		$this->smarty->goAssign(array(
		 
			"list"=>$list
		));
		$this->smarty->display("aichat_book/index.html");
	}
	public function onShow(){
		$bookid=get("bookid","i");
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		$list=M("mod_aichat_book_article")->select(array(
			"where"=>"bookid=".$bookid." AND status in(0,1) ",
			"order"=>"id ASC"
		));
		$this->smarty->goAssign(array(
			"book"=>$book,
			"list"=>$list
		));
		$this->smarty->display("aichat_book/show.html");
	}
	
	public function onWrite(){
		$this->smarty->display("aichat_book/write.html");
	}
	
	public function onWriteSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$prompt=post("prompt","x");
		if(empty($prompt)){
			$this->goAll("请输入提示",1);
		}
		//检测vip
		$aiuser=MM("aichat","aichat_user")->get($userid);
		if($aiuser["vip_etime"]<time()){
			$this->goAll("您的Vip已经到期了",11);
		}
		$num=post("num","i"); 
		$num=min(60,$num);
		$queuekey="aichat_create_text";
		$queue=new queue($queuekey);
		//生成书的章节
		$bookid=M("mod_aichat_book")->insert(array(
			"createtime"=>date("Y-m-d H:i:s"),
			"prompt"=>$prompt,
			"title"=>cutstr($prompt,30),
			"num"=>$num,
			"userid"=>$userid
		));
		$finishdata=array(
			"tablename"=>"mod_aichat_book",
			"bookid"=>$bookid,
			"num"=>$num,
			"action"=>"create_book"
		);
		$queue->lpush(array(
			"action"=>"create_book",
			"bookid"=>$bookid,
			"prompt"=>$prompt,
			"history"=>[],
			"finishdata"=>arr2str($finishdata)
		));
		$this->goAll("任务提交成功".$bookid);
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$start=get("per_page","i");
		$limit=24;
		$rscount=true;
		$list=M("mod_aichat_book")->select(array(
			"where"=>" userid=".$userid." AND status in(0,1,2)  ",
			"order"=>"bookid DESC",
			"limit"=>$limit,
			"start"=>$start
		),$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"rscount"=>$rscount,
			"per_page"=>$per_page,
			"list"=>$list
		));
		$this->smarty->display("aichat_book/my.html");
	}
	
	public function onAdd(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$bookid=get("bookid","i");
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		$type=get("type","h");
		 
		$this->smarty->goAssign(array(
			"data"=>$book
			 
		));
		if($type=='answer'){
			$this->smarty->display("aichat_book/add_answer.html");
		}else{
			$this->smarty->display("aichat_book/add.html");
		}
		
	}
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$bookid=post("bookid","i");
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		$data=M("mod_aichat_book")->postData();
		M("mod_aichat_book")->update($data,"bookid=".$bookid);
		$this->goAll("success");
	}
	public function onanswersave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$bookid=post("bookid","i");
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		if($book["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$answer=post("answer","h");
		M("mod_aichat_book")->update(array(
			"answer"=>$answer
		),"bookid=".$bookid);
		$create_article=post("create_article","i");
		if($create_article){
			$this->createArticle($bookid);
		}
		$this->goAll("success");
	}
	public function oncreatearticle(){
		$bookid=get_post("bookid","i");
		$userid=M("login")->userid;
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		if($book["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		if($this->createArticle($bookid)){
			$this->goAll("正在写书，请稍后来看");
		}else{
			$this->goAll("已经提交任务，请稍后再看",1);
		}
	}
	public function createArticle($bookid){
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		if($book["create_article"]){
			return false;
		}
		M("mod_aichat_book")->update(array(
			"create_article"=>1
		),"bookid=".$bookid);
		$num=$book["num"];
		//生成书籍文章
		$history=[
			[$book["prompt"],$book["answer"]]
		];
		$queuekey="aichat_create_text";
		$queue=new queue($queuekey);
		for($i=1;$i<=$num;$i++){
			
			$prompt2="给我第".$i."篇的内容";
			$finishdata=array(
				"bookid"=>$bookid,
				"id"=>0,
				"action"=>"create_book_article"
			);
			$queue->lpush(array(
				"action"=>"create_book_article",
				"prompt"=>$prompt2,
				"history"=>$history,
				"finishdata"=>arr2str($finishdata)
			));
		}
		return true;
	}
	
	public function onedit_article(){
		$bookid=get_post("bookid","i");
		$userid=M("login")->userid;
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		if($book["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		$list=M("mod_aichat_book_article")->select(array(
			"where"=>" bookid=".$bookid,
			"order"=>" id ASC"
		));
		$article=M("mod_aichat_book_article")->selectRow(array(
			"where"=>" bookid=".$bookid,
			"order"=>" id ASC"
		));
		$this->smarty->goAssign(array(
			"book"=>$book,
			"list"=>$list,
			"article"=>$article
		));
		$this->smarty->display("aichat_book/edit_article.html");
	}
	
	public function onDelete(){
		$bookid=get_post("bookid","i");
		$userid=M("login")->userid;
		$book=M("mod_aichat_book")->selectRow("bookid=".$bookid);
		if($book["userid"]!=$userid){
			$this->goAll("暂无权限",1);
		}
		M("mod_aichat_book")->update(array(
			"status"=>11
		),"bookid=".$bookid);
		$this->goAll("success");
	}
}