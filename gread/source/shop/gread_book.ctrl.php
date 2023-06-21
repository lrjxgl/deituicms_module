<?php
class gread_bookControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status=1 ";
		$order="sold_num DESC";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND title like '%".$keyword."%' "; 
		}
		$catid=get("catid","i");
		if($catid){
			$where.=" AND catid=".$catid;
		}
		$ops=array(
			"where"=>$where,
			"order"=>$order
		);
		$rscount=true;
		$list=MM("gread","gread_book")->Dselect($ops,$rscount);
		$ids=MM("gread","gread_shop_product")->selectCols(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"bookid"
		));
		if($list){
			foreach($list as $k=>$v){
				
				if($ids && in_array($v["bookid"],$ids)){
					$v["inshop"]=1;
				}else{
					$v["inshop"]=0;
				}
				$list[$k]=$v;
			}
		}
		$catList=M("mod_gread_book_category")->select(array(
			"where"=>" status=1 ",
			"order"=>" orderindex ASC"
		)); 
		
		$this->smarty->goAssign(array(
			"list"=>$list,
			"catList"=>$catList
		));
		$this->smarty->display("gread_book/index.html");
	}
	/**
	 * 添加图书 
	**/
	public function onShopSave(){
		$bookid=get('bookid',"i");
		$book=MM("gread","gread_book")->selectRow("bookid=".$bookid);
		if($book["status"]!=1){
			$this->goAll("书本已下架",1);
		}
		$product=MM("gread","gread_shop_product")->selectRow("bookid=".$bookid." AND shopid=".SHOPID);
		if($product){
			MM("gread","gread_shop_product")->update(array(
				"title"=>$book["title"],
				"catid"=>$book["catid"]
			),"id=".$product["id"]);
		}else{
			MM("gread","gread_shop_product")->insert(array(
				"title"=>$book["title"],
				"catid"=>$book["catid"],
				"bookid"=>$bookid,
				"shopid"=>SHOPID,
				"status"=>0
			));
		}
		$this->goAll("success");
	}
	
	public function onAdd(){
		$bookid=get_post("bookid","i");
		if($bookid){
			$data=M("mod_gread_book")->selectRow(array("where"=>"bookid={$bookid}"));			
		}
		$rss=M("mod_gread_book_category")->select($option,$rscount);
		$catlist=$child=array();
		if($rss){
			foreach($rss as $rs){
				if($rs["pid"]==0){
					$catlist[]=$rs;
				}else{
					$child[$rs["pid"]][]=$rs;
				}
			}
			foreach($catlist as $k=>$v){
				$v["child"]=$child[$v["catid"]];
				$catlist[$k]=$v;
			}
		}
		$this->smarty->goassign(array(
			"data"=>$data,
			"catlist"=>$catlist
		));
		$this->smarty->display("gread_book/add.html");
	}
	
	public function onSave(){
		$bookid=get_post("bookid","i");
		$data=M("mod_gread_book")->postData();
		if($bookid){
			M("mod_gread_book")->update($data,"bookid='$bookid'");
		}else{
			M("mod_gread_book")->insert($data);
		}
		$this->goall("保存成功");
	}
	
}