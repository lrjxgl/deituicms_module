<?php
class gread_shop_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$userid=M("login")->userid;
		$shopid=MM("gread","gread")->getShopid();
		$where=" shopid=".$shopid;
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND title like '%".$keyword."%' "; 
		}
		$catid=get("catid","i");
		if($catid){
			$where.=" AND catid=".$catid;
		}
		switch(get("statusType")){
			case 1:
				$where.=" AND status=1 ";
				break;
			case 2:
				$where.=" AND status=2 ";
				break;
		}
		switch(get("recType")){
			case 1:
				$where.=" AND isrecommend=1 ";
				break;
			case 2:
				$where.=" AND isrecommend=0 ";
				break;
		}
		$start=get("per_page","i");
		$limit=12;
		$ops=array(
			"where"=>$where,
			"fields"=>"*",
			"order"=>"id DESC",
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=M("mod_gread_shop_product")->select($ops,$rscount);
		$carts=MM("gread","gread_cart")->getBooks($userid,$shopid);
		if($list){
			$proids=array();
			foreach($list as $v){
				$proids[]=$v["bookid"];
			}
			$pros=MM("gread","gread_book")->getListByIds($proids,"bookid,title,price,imgurl");
			foreach($list as $k=>$v){
				$p=$pros[$v["bookid"]];
				$v["title"]=$p["title"];
				$v["imgurl"]=$p["imgurl"];
				$v["price"]=$p["price"];
				if(isset($carts[$v['bookid']])){
					$v['incart']=1;
				}else{
					$v['incart']=0;
				}
				$list[$k]=$v;
			}
		}
		$catList=M("mod_gread_book_category")->select(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		));
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goassign(array(
			"booklist"=>$list,
			"catList"=>$catList,
			"per_page"=>$per_page
		));
	}
	
	public function onShow(){
		$shopid=get('shopid','i');
		$bookid=get('bookid','i');
		$userid=M("login")->userid;
		$shop=MM("gread","gread_shop")->selectRow("shopid=".$shopid);
		$book=M("mod_gread_book")->selectRow("bookid=".$bookid);
		$book["imgurl"]=images_site($book["imgurl"]);
		$carts=MM("gread","gread_cart")->getBooks($userid,$shopid);
		if(isset($carts[$book['bookid']])){
			$book['incart']=1;
		}else{
			$book['incart']=0;
		}
		$this->smarty->goAssign(array(
			"shop"=>$shop,
			"book"=>$book
		));
		$this->smarty->display("gread_shop_product/show.html");
	}
}
?>