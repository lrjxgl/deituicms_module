<?php
class gread_bookControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onNew(){
		$start=get("per_page","i");
		$limit=12;
		$ops=array(
			"where"=>" status=1 ",
			"order"=>"bookid DESC",
			"limit"=>$limit,
			"start"=>$start
		);
		$rscount=true;
		$list=MM("gread","gread_book")->Dselect($ops,$rscount);
		$seo=M("seo")->get("gread","new");
		if(!$seo){
			$seo=array(
				"title"=>"新书推荐"
			);
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"seo"=>$seo
		));
		$this->smarty->display("gread_book/new.html");
	}
	public function onShow(){
		$bookid=get("bookid","i");
		 
		$book=MM("gread","gread_book")->get($bookid);
		$seo=array(
			"title"=>$book["title"],
			"description"=>$book["description"]
		);
		 
		$this->smarty->goAssign(array(
			"book"=>$book,
			"seo"=>$seo
		));
		$this->smarty->display("gread_book/show.html");
	}
	
}