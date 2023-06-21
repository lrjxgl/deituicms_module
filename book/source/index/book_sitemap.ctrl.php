<?php
class book_sitemapControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onChEmpty(){
		$list=M("mod_book_article_data")->select(array(
			"limit"=>1000,
			"order"=>"id DESC"
		));
		if($list){
			foreach($list as $v){
				if($v["content"]==""){
					M("mod_book_article")->update(array(
						"status"=>2
					),"id=".$v["id"]);
				}
			}
		}
		echo "success";
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=48;
		$ops=array(
			"where"=>" status in(0,1) AND pid>0 ",
			"order"=>"id DESC",
			"start"=>$start,
			"limit"=>$limit
		);
		$rscount=true;
		$list=M("mod_book_article")->select($ops,$rscount);
		if($list){
			foreach($list as $v){
				$bookids[]=$v["bookid"];
			}
			$books=MM("book","book")->getListByIds($bookids);
			 
			foreach($list as $k=>$v){
				$v["title"]=$books[$v["bookid"]]["title"]."之".$v["title"];
				unset($v["mp4url"]);
				$list[$k]=$v;
			}
		}
		$url="/module.php?m=book_sitemap";
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->assign(array(
			"list"=>$list,
			"pagelist"=>$pagelist,
			"seo"=>array(
				"title"=>"得推公开课",
				"description"=>"得推公开课是得推网推出的关于得推软件以及编程教程"
			)
		));
		$this->smarty->display("book_sitemap/index.html");
		
	}
	public function onShow(){
		$id=get("id",'i');
		$data=M("mod_book_article")->selectRow("id=".$id);
		if(empty($data)) exit("eee");
		unset($data["mp4url"]);
		$data["content"]=M("mod_book_article")->selectOne(array(
			"where"=>" id=".$id,
			"fields"=>"content"
		));
		$book=M("mod_book")->selectRow("bookid=".$data["bookid"]);
		$seo=array(
			"title"=>$book["title"]."之".$data["title"],
			"description"=>"《".$book["title"]."》，".$data["description"]
		);
		$this->smarty->assign(array(
			"book"=>$book,
			"data"=>$data,
			"seo"=>$seo
		));
		$this->smarty->display("book_sitemap/show.html");
	}
}