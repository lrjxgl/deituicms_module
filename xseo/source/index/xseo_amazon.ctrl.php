<?php
class xseo_amazonControl extends skymvc{
	public function oninit(){
		session_write_close();
	}
	public function onDefault(){
		
		$this->smarty->display("xseo_amazon/index.html");
	}
	public function onSql(){
		M("mod_xseo_amazon_stole_index")->query("
			ALTER TABLE `sky_mod_ershou_product`
			ADD COLUMN `baoyou`  tinyint UNSIGNED NOT NULL DEFAULT 0 AFTER `shoptype`;
		");
		echo "success";
	}
	public function onSearch(){
		$start=get("per_page","i");
		$limit=60;
		$keyword=get("keyword","h");
		if(!empty($keyword)){
			$where=" keyword='".$keyword."' ";
		}
		$asin=get("asin","h");
		if(!empty($asin)){
			$where=" asin='".$asin."' ";
		}
		$order=" pageindex ASC";
		$rscount=true;
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$list=M("mod_xseo_amazon_stole_index")->select($ops,$rscount);
		if(!empty($list)){
			$pids=[];
			$pros=[];
			foreach($list as $k=>$v){
				$pids[]=$v["productid"];
			}
			$pros=MM("xseo","xseo_amazon_product")->getListByIds($pids);
			foreach($list as $k=>$v){
				$v["product"]=$pros[$v["productid"]];
				$v["stat_word_num"]=M("mod_xseo_amazon_stole_index")->getcount("productid=".$v["productid"]);
				$v["search_word_num"]=M("mod_xseo_amazon_stole_index")->getcount("productid=".$v["productid"]." AND xfrom='search'");
				$v["ad_word_num"]=M("mod_xseo_amazon_stole_index")->getcount("productid=".$v["productid"]." AND xfrom='ad'");
				$v["recommend_word_num"]=M("mod_xseo_amazon_stole_index")->getcount("productid=".$v["productid"]." AND xfrom='recommend'"); 
				$list[$k]=$v;
			}
		} 
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$wordRow=M("mod_xseo_amazon_keyword")->selectRow("title='".$keyword."'");
		if(!empty($wordRow)){
			$wordRow["uws"]=explode(",",$wordRow["unionword"]);
		}
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"per_page"=>$per_page,
			"wordRow"=>$wordRow
		));
		
	}
	
	public function ongetword(){
		$asin=get("asin","h");
		$type=get("type","h");
		switch($type){
			case "search":
				$where=" asin='".$asin."' AND xfrom='search' ";
				break;
			case "ad":
				$where=" asin='".$asin."' AND xfrom='ad' ";
				break;
			default:
				$where=" asin='".$asin."'";
				break;
		}
		$list=M("mod_xseo_amazon_stole_index")->selectCols(array(
			"where"=>$where,
			"fields"=>"keyword"
		));
		$this->smarty->goAssign(array(
			"list"=>$list
			 
		));
	}
	
	public function onStoleUsa(){
		$content=file_get_contents("temp/amazon-usa.txt");
		$stole=new stole();
		$stole->content=$content;
		//全部列表
		$allList=$stole->cutHtml('<div class="s-main-slot s-result-list s-search-results sg-row">');
		//顶部广告
		$topList=$stole->cutHtml('<div class="_bGlmZ_content_2rsXy">');
		//品牌推荐
		$brandList=$stole->cutHtml('<div class="a-section a-spacing-base a-spacing-top-base" id="CardInstance',$content);
		//分页
		$pageList=$stole->cutHtml('<span class="s-pagination-strip">',$content);
		//记录数
		$total=$stole->cutHtml('<div class="a-section a-spacing-small a-spacing-top-small">',$content);
		echo $total;
		 
	}
	
	public function onStole(){
		set_time_limit(100);
		//获取关键词
		//$keyword=get("keyword","x");
		$queue=new queue("xseo_amazon_keyword");
		$keyword=$queue->rpop();
		if(empty($keyword)){
			$keyword="iphone";
			//exit("关键词都采集完了");
		}
		//获取ip
		$queue=new queue("xseo_amazon_ip");
		$ip=$queue->rpop();
		$ip=ip();
		//检测是否在词库
		$row=M("mod_xseo_amazon_keyword")->selectRow("title='".$keyword."'");
		if(empty($row)){
		   M("mod_xseo_amazon_keyword")->insert(array(
				"title"=>$keyword,
				"createtime"=>date("Y-m-d H:i:s"),
				"status"=>1,
				"kfrom"=>"site"
		   ));
		}
		
		for($page=1;$page<4;$page++){
			//$page=max(1,get("page","i"));
			$http="https://www.amazon.com/";
			if($page>1){
				//$url=$http.get("url","x");
				$url=$http.$nexturl;
			}else{
				$url="https://www.amazon.com/s?k=".urlencode($keyword)."&__mk_zh_CN=%E4%BA%9A%E9%A9%AC%E9%80%8A%E7%BD%91%E7%AB%99&ref=nb_sb_noss";
			}

			/*
			$c=file_get_contents($url);
			file_put_contents("temp/amazon.txt",$c);
			
			exit;
			*/
		   $stole=new stole();
		   
		   $content=file_get_contents($url);
		
		   file_put_contents("temp/amazon.txt",$content); 
		   
		   //$content=file_get_contents("temp/amazon.txt");
		   $stole->content=$content;
		   //相关搜索词
		   $c=$stole->cutHtml('<span data-component-type="text-reformulation-widget"');
		   preg_match_all('/<span class="a-size-base a-color-base s-line-clamp-2">(.*)<\/span>/iUs',$c,$arr);
		   //存入关键词
		   if(!empty($arr)){
			   $unionword=[];
			   foreach($arr[1] as $w){
				   $w=trim($w);
					$unionword[]=$w;
				   $row=M("mod_xseo_amazon_keyword")->selectRow("title='".$w."'");
				   if(empty($row)){
					   M("mod_xseo_amazon_keyword")->insert(array(
							"title"=>$w,
							"createtime"=>date("Y-m-d H:i:s"),
							"status"=>1,
							"kfrom"=>"site"
					   ));
				   }
			   }
			   if(!empty($unionword)){
				   M("mod_xseo_amazon_keyword")->update(array(
						"unionword"=>implode(",",$unionword)
				   ),"title='".$keyword."'");
			   }
			   
		   }
			$c=$stole->cutHtml('<div class="a-section a-spacing-small a-spacing-top-small">',$content);
			preg_match("/显示： \d+-\d+条， 共([\d,]+)条/iUs",$c,$arr);
			M("mod_xseo_amazon_keyword")->update(array(
				"total_num"=>$arr[1]
			),"title='".$keyword."'");
			 
			$stole->content=$content;
		   //分页
		   preg_match('/<div class="a-section a-text-center s-pagination-container" role="navigation">.*<\/div>/iUs',$stole->content,$uu);	
		  
		   preg_match_all("/href=\"(.*)\"/iUs",$uu[0],$urls);
		
			$url2=$url3=$nexturl="";
			if(!empty($urls[1])){
				foreach($urls[1] as $u){
					if(strpos($u,"page=3")!==false){
						$url3=$u;
					}
					if(strpos($u,"page=2")!==false){
						$url2=$u;
					}
				}
			}
			if($page==1){
				$nexturl=$url2;
			}elseif($page==2){
				$nexturl=$url3;
			}
			
			 
			 
		   preg_match_all('/<div data-asin="(.*)".*>/iUs',$stole->content,$arr);
			if(empty($arr[1])){
				echo "出错了".$page;
				
				return false;
			}
		   $asins=[];
		   foreach($arr[1] as $v){
			   if(!empty($v)){
				   $asins[]=$v;
			   }
		   }
		   
		   foreach($asins as $k=>$asin){
			   $item=$stole->cutHtml("<div data-asin=\"".$asin."\"",$content);
			   //匹配图片
			   preg_match('/<img class="s-image" src="(.*)"/iUs',$item,$arr);
			   $img=$arr[1];
			   //匹配链接
			   preg_match('/<a class="a-link-normal s-no-outline" href="(.*)">/iUs',$item,$arr);
				$link=$arr[1];
			   //匹配文字
			   preg_match('/<span class="a-size-medium a-color-base a-text-normal">(.*)<\/span>/iUs',$item,$arr);
				$title=$arr[1];
			   //匹配评价
			   preg_match('/<span class="a-icon-alt">.*([\d\.]+) .*<\/span>/iUs',$item,$arr);
			   $raty=$arr[1];
			   //匹配评价数量
			   preg_match('/<a class="a-link-normal s-underline-text s-underline-link-text s-link-style" href=".*">.*<span class="a-size-base s-underline-text">(.*)<\/span>.*<\/a>/iUs',$item,$arr);
			   $raty_num=intval($arr[1]);
			   //匹配价格
			   preg_match('/<span class="a-offscreen">.*([\d\.]+)<\/span>/iUs',$item,$arr);
			   $price=$arr[1];
			   //匹配促销价
			   preg_match('/<span class="a-price a-text-price" data-a-size="b" data-a-strike="true" data-a-color="secondary">.*([\d\.]+)<\/span>/iUs',$item,$arr);
			   $lower_price=$arr[1];
			   //cel_widget_id
			   preg_match('/cel_widget_id="(.*)"/iUs',$item,$arr);
			   $widget_id=$arr[1];
			   //是否来自广告
			   $xfrom="search";
			   if(strpos($item,'推广')!==false ||strpos($item,'Sponsored')!==false || strpos($link,'/gp/slredirect')!==false ){
				   $xfrom="ad";
			   }
				
			 /*   
			  print_r(array(
				$asin,$img,$link,$title,$raty,$price,$lower_price,$widget_id
			  ));
			  */
			  $row=M("mod_xseo_amazon_product")->selectRow("asin='".$asin."'");
			  $pageindex=max(0,$page-1)*16+$k;
			  if(empty($row)){
				  $productid=M("mod_xseo_amazon_product")->insert(array(
					"asin"=>$asin,
					"title"=>$title,
					"imgurl"=>$img,
					"linkurl"=>$link,
					"raty"=>$raty,
					"raty_num"=>$raty_num,
					"price"=>$price,
					"lower_price"=>$lower_price
				  ));
			  }else{
				  $productid=$row["productid"];
			  }
			  $row=M("mod_xseo_amazon_stole_index")->selectRow("productid=".$productid." AND keyword='".$keyword."' AND createtime like '".date("Y-m-d")."%' ");
			  if(!empty($row)){
				  M("mod_xseo_amazon_stole_index")->delete("id=".$row["id"]);
			  }
			  M("mod_xseo_amazon_stole_index")->insert(array(
				"productid"=>$productid,
				"widget_id"=>$widget_id,
				"pageindex"=>$pageindex,
				"keyword"=>$keyword,
				"ip"=>$ip,
				"asin"=>$asin,
				"xfrom"=>$xfrom,
				"createtime"=>date("Y-m-d H:i:s")
			  ));
			   
		   }
	   }
	   /*
	   //跳转
	   if($page<3){
	   	header("Location: /module.php?m=xseo_amazon&a=stole&keyword=".$keyword."&page=".($page+1)."&url=".urlencode($nexturl));
	   }
	   */
		echo "采集成功";
	}
	
}