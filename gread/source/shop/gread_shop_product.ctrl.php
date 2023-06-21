<?php
	class gread_shop_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID;
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
			if($list){
				$proids=array();
				foreach($list as $v){
					$proids[]=$v["bookid"];
				}
				$pros=MM("gread","gread_book")->getListByIds($proids,"bookid,title,imgurl");
				foreach($list as $k=>$v){
					$p=$pros[$v["bookid"]];
					$v["title"]=$p["title"];
					$v["imgurl"]=$p["imgurl"];
					
					$list[$k]=$v;
				}
			}
			$catList=M("mod_gread_book_category")->select(array(
				"where"=>" status=1 ",
				"order"=>" orderindex ASC"
			)); 
			$this->smarty->goassign(array(
				"list"=>$list,
				"catList"=>$catList
			));
			$this->smarty->display("gread_shop_product/index.html");
		}
		
		public function onjinhuo(){
			$books=M("mod_gread_book")->select(array(
				"where"=>" status=1 "
			));
			if(empty($books)) $this->goAll("暂时无法进货",1);
			$products=M("mod_gread_shop_product")->select(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"bookid,total_num"
			));
			$pros=array();
			if($products){
				foreach($products as $p){
					$pros[$p['bookid']]=$p['total_num'];
				}
			}
			 
			$carts=M("mod_gread_shop_incart")->select(array(
				"where"=>"shopid=".SHOPID
			));
			$bcart=array();
			if($carts){
				foreach($carts as $v){
					$bcart[$v['bookid']]=$v;
				}
				
			}
			foreach($books as &$v){
				if(isset($bcart[$v['bookid']])){
					$v['cart_num']=$bcart[$v['bookid']]['num'];
				}else{
					$v['cart_num']=0;
				}
				$v['product_num']=0;
				if(isset($pros[$v['bookid']])){
					$v['product_num']=$pros[$v['bookid']];
				}
			}
			
			$this->smarty->assign(array(
				"books"=>$books,
			 
			));
			$this->smarty->display("gread_shop_product/jinhuo.html");
		}
		
		public function onStatus(){
			$productid=get("productid","i");
			$p=M("mod_gread_shop_product")->selectRow("id=".$productid);
			if($p["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_gread_shop_product")->update(array(
				"status"=>$status
			),"id=".$productid);
			$this->goAll("修改成功",0,$status);
		}
		
		public function onRecommend(){
			$productid=get("productid","i");
			$p=M("mod_gread_shop_product")->selectRow("id=".$productid);
			if($p["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_gread_shop_product")->update(array(
				"isrecommend"=>$status
			),"id=".$productid);
			$this->goAll("修改成功",0,$status);
		}
		
		public function onchangenumsave(){
			$productid=post("productid","i");
			$total_num=post("total_num","i");
			$p=M("mod_gread_shop_product")->selectRow("id=".$productid);
			if($p["out_num"]>$total_num){
				$this->goAll("库存数量少于借出数量",1);
			}
			$free_num=$total_num-$p["out_num"];
			M("mod_gread_shop_product")->update(array(
				"total_num"=>$total_num,
				"free_num"=>$free_num,
			),"id=".$productid);
			$this->goAll("更改成功",0,array(
				"total_num"=>$total_num,
				"free_num"=>$free_num,
			));
		}
		
	}
?>