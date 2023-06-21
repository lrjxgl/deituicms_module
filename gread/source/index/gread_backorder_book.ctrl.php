<?php
class gread_backorder_bookControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_gread_backorder_book")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$ids[]=$v['bookid'];
			}
			$books=MM("gread","gread_book")->getListByIds($ids);
			foreach($data as $k=>$v){
				$v['title']=$books[$v['bookid']]['title'];
				$v['imgurl']=$books[$v['bookid']]['imgurl'];
				$v['book_money']=$books[$v['bookid']]['price'];
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("gread_backorder_book/index.html");	
	}
	
	
	
}
?>