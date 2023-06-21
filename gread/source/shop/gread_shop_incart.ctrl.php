<?php
	class gread_shop_incartControl extends skymvc{
		
		public function __construct(){
			parent::__construct();			
		}
		
		public function onDefault(){
			
			$this->smarty->display("gread_shop_incart/index.html");
		}
		
		public function onAdd(){
			$bookid=get('bookid','i');
			$product=M("mod_gread_shop_product")->selectRow("bookid=".$bookid." AND shopid=".SHOPID);
			if(!$product){
				M("mod_gread_shop_product")->insert(array(
					"shopid"=>SHOPID,
					"bookid"=>$bookid,
				));
			}
			$row=M("mod_gread_shop_incart")->selectRow("bookid=".$bookid." AND shopid=".SHOPID);
			$num=1;
			if($row){
				$num=$row['num']+1;
				M("mod_gread_shop_incart")->update(array("num"=>$row['num']+1),"bookid=".$bookid." AND shopid=".SHOPID);
			}else{
				M("mod_gread_shop_incart")->insert(array(
						"num"=>1,
						"bookid"=>$bookid,
						"shopid"=>SHOPID
				));
			}
			$res=array(
				"bookid"=>$bookid,
				"num"=>$num
			);
			$this->goAll("加入成功",0,$res);
		}
		
		public function onMinus(){
			$bookid=get('bookid','i');
			$product=M("mod_gread_shop_product")->selectRow("bookid=".$bookid." AND shopid=".SHOPID);
			if(!$product){
				M("mod_gread_shop_product")->insert(array(
					"shopid"=>SHOPID,
					"bookid"=>$bookid,
				));
			}
			$row=M("mod_gread_shop_incart")->selectRow("bookid=".$bookid." AND shopid=".SHOPID);
			$num=1;
			if($row){
				$num=$row['num']-1;
				if($num==0){
					M("mod_gread_shop_incart")->delete("bookid=".$bookid." AND shopid=".SHOPID);
				}else{
					M("mod_gread_shop_incart")->update(array("num"=>$row['num']-1),"bookid=".$bookid." AND shopid=".SHOPID);
				}
				
			}else{
				$num=0;
			}
			$res=array(
				"bookid"=>$bookid,
				"num"=>$num
			);
			$this->goAll("加入成功",0,$res);
		}  
		
		
	}
?>