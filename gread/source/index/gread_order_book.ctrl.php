<?php
class gread_order_bookControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$data=M("mod_gread_order_book")->select($option,$rscount);
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
			$carts=MM("gread","gread_backcart")->getBooks($userid,$shopid);
			if($data){
				foreach($data as &$v){
					if(isset($carts[$v['bookid']])){
						$v['incart']=1;
					}else{
						$v['incart']=0;
					}
				}
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data,
			"carts_num"=>count($carts)
		));
		$this->smarty->display("gread_order_book/index.html");	
	}
	
	
	
}
?>