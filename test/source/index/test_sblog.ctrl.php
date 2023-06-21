<?php
class test_sblogControl extends skymvc{
	public $md="sblog";
	public  function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$this->onInitUser();
		$this->onBanner();
		$this->onBlog();
	}
	public function onInitUser(){
		$row=M("user")->selectRow("userid=1");
		if(!$row){
			M("user")->insert(array(
				"userid"=>1,
				"nickname"=>"老雷"
			));
		}
		
	}
	public function onBanner(){
		
		$a=M("ad_tags")->selectRow("tagno='uniapp-{$this->md}-index'");
		
		$tag_id=2; 
		if(empty($a)){
			
			$tag_id_2nd=M("ad_tags")->insert(array(
				"title"=>"uniapp-{$this->md}-index",
				"tagno"=>"uniapp-{$this->md}-index",
				"pid"=>$tag_id,
				"status"=>2
			));
		}else{
			$tag_id_2nd=$a["tag_id"];
		}
		M("ad")->query("delete from ".table("ad")." where tag_id_2nd=".$tag_id_2nd);
		M("ad")->insert(array(
			"tag_id"=>$tag_id,
			"title"=>$this->navlist[$i],
			"tag_id_2nd"=>$tag_id_2nd,
			"starttime"=>time()-3600,
			"endtime"=>time()+3600*24*360*10,
			"status"=>2,
			"imgurl"=>"attach/demo/banner/banner-deituicms.jpg"
		));
		//wap
		$tag_id=2;
		$a=M("ad_tags")->selectRow("tagno='wap-{$this->md}-index'");
		if(empty($a)){	
			
			$tag_id_2nd=M("ad_tags")->insert(array(
				"title"=>"wap-{$this->md}-index",
				"tagno"=>"wap-{$this->md}-index",
				"pid"=>$tag_id,
				"status"=>2
			));
			
			
		}else{
			$tag_id_2nd=$a["tag_id"];
		}
		M("ad")->query("delete from ".table("ad")." where tag_id_2nd=".$tag_id_2nd);
		M("ad")->insert(array(
			"tag_id"=>$tag_id,
			"title"=>$this->navlist[$i],
			"tag_id_2nd"=>$tag_id_2nd,
			"starttime"=>time()-3600,
			"endtime"=>time()+3600*24*360*10,
			"status"=>2,
			"imgurl"=>"attach/demo/banner/banner-deituicms.jpg"
		));
		echo "banner";
	}
	public function onBlog(){
		$list=array(
			0=>array(
				
				"title"=>"福鼎白茶有多好",
				"content"=>"福鼎白茶很不错吧，福鼎白茶网专注于福鼎白茶人的社交平台",
				"imgurl"=>"attach/demo/product/35885353f30dfcfa440e9229e4248206.jpg"
			),
			1=>array(
				"title"=>"福鼎有多美",
				"content"=>"福鼎有五星级风景区太姥山，最美十大海岛嵛山岛，美丽沙滩牛栏岗",
				"imgurl"=>"attach/demo/product/35885353f30dfcfa440e9229e4248206.jpg"
			),
			2=>array(
				"title"=>"厦门网络科技有限公司专业从事互联网营运和推广",
				"content"=>"厦门网络科技有限公司专业从事互联网营运和推广，需要的伙伴可以联系我们",
				"imgurl"=>"attach/demo/product/35885353f30dfcfa440e9229e4248206.jpg"
			),
			3=>array(
				"title"=>"deituiCMS是一款强大的CMS系统，基于插件扩展模式，可以快速实现需要的功能",
				"content"=>"deituiCMS基于php+mysql开发，采用插件开发模式，可以根据不同的需求进行组合。有需求欢迎咨询老雷，15985840591",
				"imgurl"=>"attach/demo/product/35885353f30dfcfa440e9229e4248206.jpg"
			),
		);
		foreach($list as $rs){
			$id=M("mod_{$this->md}_blog")->insert(array(
				 
				"content"=>$rs["content"], 
				 
				"imgurl"=>$rs["imgurl"],
				"imgsdata"=>$rs["imgurl"],
				"status"=>1,
				"isrecommend"=>1, 
				"userid"=>1, 
				"createtime"=>date("Y-m-d H:i:s")
			));
		}
		
		echo "blog";
	}
	
}
?>