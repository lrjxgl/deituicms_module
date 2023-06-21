<?php
class book_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$where=" isdelete=0 ";
		$url="/moduleadmin.php?m=book_order";
		 
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("book","book_order")->Dselect($option,$rscount);
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
		$this->smarty->display("book_order/index.html");
		
	}
	
	public function onAdd(){
		$this->smarty->display("book_order/add.html");
	}
	
	public function onSave(){
		$data=MM("book","book_order")->postData();
		MM("book","book_order")->insert($data);
		$this->goAll("保存成功");
	}
	public function onDelete(){
		$orderid=get('orderid','i');
		MM("book","book_order")->update(array(
			"isdelete"=>1
		),"orderid=".$orderid);
		$this->goAll("删除成功");
	}
	
	public function onSearchTitle(){
		$title=get_post('title');
		$book=M("mod_book")->selectRow("title='".$title."' ");
		if($book){
			$this->goAll("success",0,$book['bookid']);
		}else{
			$this->goAll("error",1);
		}
	}
	
	public function onSearchUser(){
		$nickname=get_post('nickname');
		$user=M("user")->selectRow("nickname='".$nickname."' ");
		if($user){
			$this->goAll("success",0,$user['userid']);
		}else{
			$this->goAll("error",1);
		}
	}
	
}
?>