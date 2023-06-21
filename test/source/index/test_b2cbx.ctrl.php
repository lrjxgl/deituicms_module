<?php
class test_b2cbxControl extends skymvc{
	public $md="b2cbx";
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
				"link1"=>"/module.php?m={$this->md}_product&a=list&catid=".$catids[$i]
				
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
		$file="attach/demo/product/data.txt";
		M("mod_{$this->md}_category")->query("truncate ".table("mod_{$this->md}_product")." ");
		M("mod_{$this->md}_category")->query("truncate ".table("mod_{$this->md}_product_data")." ");
		$maxcatid=M("mod_{$this->md}_category")->selectOne(array(
			"fields"=>"catid",
			"order"=>"catid DESC"
		));
		$c=file_get_contents($file);
		$res=json_decode($c,true);
		
		foreach($res as $rs){
			$row=M("mod_{$this->md}_product")->selectRow("title='".$rs["title"]."'");
			if($row){
				continue;
			}
			$id=M("mod_{$this->md}_product")->insert(array(
				"title"=>$rs["title"],
				"price"=>rand(10,1000),
				"imgurl"=>$rs["imgurl"],
				"status"=>1,
				"catid"=>rand(1,$maxcatid),
				"isrecommend"=>rand(0,1),
				"total_num"=>1000,
				"createtime"=>date("Y-m-d H:i:s")
			));
			M("mod_{$this->md}_product_data")->insert(array(
				"id"=>$id
			));
		}
		file_put_contents("attach/demo/lock","");
		echo "success";
	}
	
}
?>