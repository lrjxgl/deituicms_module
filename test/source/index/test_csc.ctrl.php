<?php
class test_cscControl extends skymvc{
	public $md="csc";
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
		$this->onShop_product_category();
		$this->oncategory();
		$this->onShop();
		$this->onNav();
		$this->onBanner();
		
		
		$this->onShop_product();
	}
	public function onThumb(){
		$files=glob("attach/demo/shop/*");
		$img=new Image();
		foreach($files as $file){
			if($file=="attach/demo/shop/data.txt") continue;
			if(1==1 || !file_exists($file.".100x100.jpg")){
				$img->makethumb($file.".100x100.jpg",$file,100,100,1);
				$img->makethumb($file.".small.jpg",$file,100,100);
				$img->makethumb($file.".middle.jpg",$file,100,100);
			}
		}
		print_r($files);
	}
	public function onNav(){
		$catids=M("mod_csc_category")->selectCols(array(
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
		$navs=array(
			1=>"吃的",
			2=>"喝的",
			3=>"穿的",
			4=>"用的",
			5=>"美容",
			6=>"住的",
			7=>"汽车",
			8=>"飞机"
		);
		for($i=1;$i<=8;$i++){
			M("ad")->insert(array(
				"title"=>$navs[$i],
				"tag_id"=>$tag_id,
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
		for($i=1;$i<=8;$i++){
			M("ad")->insert(array(
				"title"=>$navs[$i],
				"tag_id"=>$tag_id,
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
		M("ad")->insert(array(
			"tag_id"=>$tag_id,
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
		M("ad")->insert(array(
			"tag_id"=>$tag_id,
			"tag_id_2nd"=>$tag_id_2nd,
			"starttime"=>time()-3600,
			"endtime"=>time()+3600*24*360*10,
			"status"=>2,
			"imgurl"=>"attach/demo/banner/banner-deituicms.jpg"
		));
		echo "banner";
	}
	public function onSHop(){
		$c=file_get_contents("attach/demo/shop/data.txt");
		$arr=explode("\n",$c);
		$list=array();
		foreach($arr as $a){
			if(empty($a)) continue;
			$barr=explode("|",$a);
			$list[]=$data=array(
				"shopname"=>$barr[0],
				"title"=>$barr[0],
				"imgurl"=>"/attach/demo/shop/".$barr[1],
				"address"=>$barr[2],
				"createtime"=>date("Y-m-d H:i:s"),
				"status"=>1,
				"isrecommend"=>rand(0,1)
			);
			 
			if(!M("mod_csc_shop")->selectRow("shopname='".$barr[0]."'")){
				M("mod_csc_shop")->insert($data);
			}
		}
		echo "success";
	}
	public function onShop_product_category(){
		$shopList=M("mod_csc_shop")->select(array());
		$cats=array(
			"吃的",
			"穿的",
			"用的",
			"裙子",
			"零食",
			"饮料",
			"鞋子",
			"口红",
			"护肤"
		);
		M("mod_csc_shop_product_category")->delete("1");
		foreach($shopList as $shop){
			shuffle($cats);
			foreach($cats as $k=>$cat){
				M("mod_csc_shop_product_category")->insert(array(
					"title"=>$cat,
					"shopid"=>$shop["shopid"],
					"status"=>1
				));
				if($k>6){
					break;
				}
			}
		}
		echo "cat success";
	}
	public function oncategory(){
		$cats=array(
			array(
				"title"=>"衣服",
				"children"=>array(
					"衬衫",
					"短袖",
					"长袖",
					"短裙",
					"长裙",
					"连衣裙"
				)
			),
			array(
				"title"=>"裤子",
				"children"=>array(
					"牛仔裤",
					"短裤",
					"内裤",
					"长裤",
					"百伦",
					"黑皮"
				)
			),array(
				"title"=>"生鲜",
				"children"=>array(
					"水果",
					"饮料",
					"螃蟹",
					"海螺",
					"鱼类",
					"鲨鱼"
				)
			),array(
				"title"=>"外卖",
				"children"=>array(
					"水果",
					"饮料",
					"螃蟹",
					"海螺",
					"鱼类",
					"鲨鱼"
				)
			),array(
				"title"=>"美食",
				"children"=>array(
					"水果",
					"饮料",
					"螃蟹",
					"海螺",
					"鱼类",
					"鲨鱼"
				)
			),
			
		);
		M("mod_csc_category")->delete("1");
		foreach($cats as $c){
			$pid=M("mod_csc_category")->insert(array(
				"status"=>1,
				"title"=>$c["title"]
			));
			foreach($c["children"] as $cc){
				M("mod_csc_category")->insert(array(
					"status"=>1,
					"title"=>$cc,
					"pid"=>$pid
				));
			}
		}
		echo "success";
	}
	public function onShop_product(){
		$shopList=M("mod_csc_shop")->select(array());
		$file="attach/demo/product/data.txt";
		$table="mod_csc_product";
		$c=file_get_contents($file);
		$res=json_decode($c,true);
		M($table)->delete("1");
		$mcids=M("mod_csc_category")->selectCols(array(
			"fields"=>"catid",
			"where"=>" status=1 "
		));
		foreach($shopList as $shop){
			$catids=M("mod_csc_shop_product_category")->selectCols(array(
				"fields"=>"catid",
				"where"=>" shopid=".$shop["shopid"]
			));
			print_r($catids);
			foreach($res as $k=>$rs){
				shuffle($catids);
				shuffle($mcids);
				$id=M($table)->insert(array(
					"shopid"=>$shop["shopid"],
					"title"=>$rs["title"],
					"price"=>rand(10,1000),
					"imgurl"=>$rs["imgurl"],
					"status"=>1,
					"shop_catid"=>$catids[0],
					"catid"=>$mcids[0],
					"isrecommend"=>rand(0,1),
					"total_num"=>1000,
					"createtime"=>date("Y-m-d H:i:s")
				));
				M("{$table}_data")->insert(array(
					"id"=>$id
				));
				$rd=rand(10,100);
				if($k>$rd){
					break;
				}
			}
			
		}
		echo "product_success";
	}
	
}