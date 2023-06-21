<?php
class gread_cartControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$shopid=MM("gread","gread")->getShopid();
		$shop=MM("gread","gread_shop")->selectRow("shopid=".$shopid);
		$userid=M("login")->userid;
		$sql="select a.*,b.* 
				from  ".table('mod_gread_cart')." as a 
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
		//判断是否有资格
		if(strtotime($guser["endtime"])<time()){
			$this->goAll("您的借书卡已经到期了",1);
		}
		$this->smarty->goAssign(array(
				"shop"=>$shop,
				"catlist"=>$catlist,
				"guser"=>$guser,
				"booklist"=>$booklist
			));
		$this->smarty->display("gread_cart/index.html");
	}
	
	public function onToggle(){
		$shopid=get('shopid','i');
		$bookid=get('bookid','i');
		$product=M("mod_gread_shop_product")->selectRow("shopid=".$shopid." AND bookid=".$bookid);
		if(empty($product)){
			$this->goAll("书籍不存在",1);
		}
		
		$userid=M("login")->userid;
		//判断是否借了未还
		$b=M("mod_gread_order_book")->selectRow("userid=".$userid."   AND isback=0 AND productid=".$product['id']);
		if($b){
			$this->goAll("每种图书只能借一本",1);
		}
		$row=M("mod_gread_cart")->selectRow(array(
			"where"=>" shopid=".$shopid." AND bookid=".$bookid." AND userid=".$userid
		));
		if($row){
			M("mod_gread_cart")->delete("id=".$row['id']);
			M("mod_gread_shop_product")->update(array(
				"free_num"=>$product["free_num"]+1,
				"out_num"=>$product["out_num"]-1
			),"id=".$product["id"]);
			$this->goAll("success",0,array("op"=>"delete"));
		}else{
			if($product["free_num"]==0){
				$this->goAll("本书借光了",1);
			}
			M("mod_gread_shop_product")->update(array(
				"free_num"=>$product["free_num"]-1,
				"out_num"=>$product["out_num"]+1
			),"id=".$product["id"]);
			M("mod_gread_cart")->insert(array(
				"shopid"=>$shopid,
				"userid"=>$userid,
				"bookid"=>$bookid,
				"productid"=>$product['id']
			));
			$this->goAll("success",0,array("op"=>"add"));
		}
		
	}
	
}
?>