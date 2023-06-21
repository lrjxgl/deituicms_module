<?php
class test_fenleiControl extends skymvc{
	public $md="fenlei";
	public $navlist=array(
		1=>"吃的",
		2=>"喝的",
		3=>"鞋子",
		4=>"用的",
		5=>"煮的",
		6=>"裙子",
		7=>"家装",
		8=>"衣服"
	);
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$this->onCategory();
		$this->onNav();
		$this->onBanner();
		
		$this->onProduct();
		$this->onGroup();
	}
	
	public function onNav(){
		$catids=M("mod_{$this->md}_category")->selectCols(array(
			"where"=>" status=1 ",
			"fields"=>" catid"
		));
		$a=M("ad_tags")->selectRow("tagno='uniapp-{$this->md}-nav'");
		if($a){
			M("ad")->delete(" tag_id_2nd=".$a["tag_id"]." ");
		}
		$b=M("ad_tags")->selectRow("tagno='wap-{$this->md}-nav'");
		if($b){
			M("ad")->delete(" tag_id_2nd=".$b["tag_id"]." ");
		}
		$tag_id=1; 
		if(empty($a)){
			
			$tag_id_2nd=M("ad_tags")->insert(array(
				"title"=>"uniapp-{$this->md}-nav",
				"tagno"=>"uniapp-{$this->md}-nav",
				"pid"=>$tag_id,
				"status"=>2
			));
		}else{
			$tag_id_2nd=$a["tag_id"];
		}
		M("ad")->query("delete from ".table("ad")." where tag_id_2nd=".$tag_id_2nd);
		for($i=1;$i<=8;$i++){
			M("ad")->insert(array(
				"tag_id"=>$tag_id,
				"title"=>$this->navlist[$i],
				"tag_id_2nd"=>$tag_id_2nd,
				"starttime"=>time()-3600,
				"endtime"=>time()+3600*24*360*10,
				"status"=>2,
				"link1"=>"../../page{$this->md}/{$this->md}_product/list?catid=".$catids[$i],
				"imgurl"=>"attach/demo/nav/{$i}.png"
			));
		}
		//wap
		$tag_id=1;
		$a=$b;
		if(empty($a)){	
			
			$tag_id_2nd=M("ad_tags")->insert(array(
				"title"=>"wap-{$this->md}-nav",
				"tagno"=>"wap-{$this->md}-nav",
				"pid"=>$tag_id,
				"status"=>2
			));
			
			
		}else{
			$tag_id_2nd=$a["tag_id"];
		}
		M("ad")->query("delete from ".table("ad")." where tag_id_2nd=".$tag_id_2nd);
		for($i=1;$i<=8;$i++){
			M("ad")->insert(array(
				"tag_id"=>$tag_id,
				"title"=>$this->navlist[$i],
				"tag_id_2nd"=>$tag_id_2nd,
				"starttime"=>time()-3600,
				"endtime"=>time()+3600*24*360*10,
				"status"=>2,
				"imgurl"=>"attach/demo/nav/{$i}.png",
				"link1"=>"/module.php?m={$this->md}&a=list&catid=".$catids[$i]
				
			));
		}
		echo "banner";
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
	
	public function onCategory(){
		require ROOT_PATH."attach/demo/category/data.php";
		M("mod_{$this->md}_category")->query("truncate ".table("mod_{$this->md}_category")." ");
		foreach($catlist as $v){
			$row=M("mod_{$this->md}_category")->selectRow("title='".$v["title"]."'");
			$pid=$row["catid"];
			if(empty($row)){
				$pid=M("mod_{$this->md}_category")->insert(array(
					"status"=>1,
					"title"=>$v["title"]
				));
			}
			foreach($v["child"] as $cc){
				$row=M("mod_{$this->md}_category")->selectRow("title='".$cc."'");
				if(empty($row)){
					M("mod_{$this->md}_category")->insert(array(
						"status"=>1,
						"pid"=>$pid,
						"title"=>$cc,
						"imgurl"=>"attach/demo/category/done/".md5($cc).".png"
					));
				}
			}
		}
		echo "success";
	}
	public function onProduct(){
		 
		M("mod_{$this->md}_category")->query("truncate ".table("mod_{$this->md}")." ");

		$maxcatid=M("mod_{$this->md}_category")->selectOne(array(
			"fields"=>"catid",
			"order"=>"catid DESC"
		));
		 
		$res=array(
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
		foreach($res as $rs){
			$row=M("mod_{$this->md}")->selectRow("title='".$rs["title"]."'");
			if($row){
				continue;
			}
			$id=M("mod_{$this->md}")->insert(array(
				"title"=>$rs["title"],
				"content"=>$rs["content"], 
				"description"=>$rs["content"],
				"imgurl"=>$rs["imgurl"],
				"status"=>1,
				"catid"=>rand(1,$maxcatid),
				"isrecommend"=>rand(0,1),
				"userid"=>1, 
				"createtime"=>date("Y-m-d H:i:s")
			));
			 
		}
		file_put_contents("attach/demo/lock","");
		echo "success";
	}
	
	public function onGroup(){
		M("mod_fenlei_tags")->delete("1");
		
		$tagid=M("mod_fenlei_tags")->insert(array(
			"title"=>"推荐",
			"gkey"=>"recommend",
			"gnum"=>12,
			"status"=>1
		));
		$tagid2=M("mod_fenlei_tags")->insert(array(
			"title"=>"热门",
			"gkey"=>"hot",
			"gnum"=>12,
			"status"=>1
		));
		$tagid3=M("mod_fenlei_tags")->insert(array(
			"title"=>"最新",
			"gkey"=>"new",
			"gnum"=>12,
			"status"=>1
		));
		$list=M("mod_fenlei")->selectCols(array(
			"where"=>" status=1 ",
			"limit"=>24
		));
		foreach($list as $productid){
			M("mod_fenlei_tags_index")->insert(array(
				"tagid"=>$tagid,
				"objectid"=>$productid
			));
			M("mod_fenlei_tags_index")->insert(array(
				"tagid"=>$tagid2,
				"objectid"=>$productid
			));
			M("mod_fenlei_tags_index")->insert(array(
				"tagid"=>$tagid3,
				"objectid"=>$productid
			));
		}
		echo "group";
	}
	
}
?>