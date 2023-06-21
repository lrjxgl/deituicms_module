<?php
class gread_backcartControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$shopid=get('shopid','i');
		$shop=MM("gread","gread_shop")->selectRow("shopid=".$shopid);
		$userid=M("login")->userid;
		$sql="select a.*,b.* 
				from  ".table('mod_gread_backcart')." as a 
				left join  ".table('mod_gread_book')." as b
				on a.bookid=b.bookid
				where a.shopid=".$shopid." AND a.userid=".$userid." 				
		";
		$booklist=M("mod_gread")->getAll($sql);
		if($booklist){
			foreach($booklist as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$booklist[$k]=$v;
			}
		}
		$guser=MM("gread","gread_user")->get($userid);
		$this->smarty->goAssign(array(
				"shop"=>$shop,
				"catlist"=>$catlist,
				"guser"=>$guser,
				"booklist"=>$booklist
			));
		$this->smarty->display("gread_backcart/index.html");
	}
	
	public function onBooks(){
		$userid=M("login")->userid;
		$option=array(
			"where"=>"userid=".$userid." AND isback=0 AND status=1  ",
			"order"=>"endtime ASC "
		);
		$data=M("mod_gread_order_book")->select($option,$rscount);
		$shops=array();
		if($data){
			foreach($data as $v){
				$ids[]=$v['bookid'];
				$shopids[]=$v['shopid'];
			}
			$shops=MM("gread","gread_shop")->getListByIds($shopids);
			$books=MM("gread","gread_book")->getListByIds($ids);
			foreach($data as $k=>$v){
				$v['title']=$books[$v['bookid']]['title'];
				$v['imgurl']=images_site($books[$v['bookid']]['imgurl']);
				$v['book_money']=$books[$v['bookid']]['price'];
				$data[$k]=$v;
			}
								
			foreach($data as $b){
				$shops[$b['shopid']]['books'][]=$b;
			}
			foreach($shops as $kk=>$shop){
				$carts=MM("gread","gread_backcart")->getBooks($userid,$shop['shopid']);
				
				foreach($shop['books'] as $k=>$v){
					if(isset($carts[$v['bookid']])){
						$v['incart']=1;
					}else{
						$v['incart']=0;
					}
					$shop['books'][$k]=$v;
				}
				$shop['carts_num']=empty($carts)?0:count($carts);
				$shops[$kk]=$shop;
			} 
		}
		$this->smarty->goAssign(array(
			"shops"=>$shops,
		 
		));
		$this->smarty->display("gread_backcart/books.html");	
	}
	
	public function onToggle(){
		$shopid=get('shopid','i');
		$bookid=get('bookid','i');
		$order_bookid=get('order_bookid','i');
		$userid=M("login")->userid;
		$product=M("mod_gread_shop_product")->selectRow("shopid=".$shopid." AND bookid=".$bookid);
		if(empty($product)){
			$this->goAll("书籍不存在",1);
		}
		$row=M("mod_gread_backcart")->selectRow(array(
			"where"=>" shopid=".$shopid." AND bookid=".$bookid." AND order_bookid=".$order_bookid." AND userid=".$userid
		));
		if($row){
			M("mod_gread_backcart")->delete("id=".$row['id']);
			$this->goAll("success",0,array("op"=>"delete"));
		}else{
			M("mod_gread_backcart")->insert(array(
				"shopid"=>$shopid,
				"userid"=>$userid,
				"bookid"=>$bookid,
				"productid"=>$product['id'],
				"order_bookid"=>$order_bookid
			));
			$this->goAll("success",0,array("op"=>"add"));
		}
		
	}
	
}
?>