<?php
class search_spiderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
		$this->loadModuleModel("search",array("mod_search"));
	}
	
	public function onDefault(){
		set_time_limit(0);
		
		 ob_implicit_flush(true); 
		 $config=M("mod_search_config")->selectRow();
		 if(!$config){
			$config=array(
				"threads"=>20,
				"steps"=>9
			); 
		 }
		 $where="islock=0 AND step<".$config['steps']."   ";
		 $domain=get('domain','h');
		 $basedomain=getBaseDomain($domain);
		 if($domain){
			$where.=" AND basedomain='".$basedomain."' "; 
		 }
		 
		$data=M("mod_search_spider")->select(array(
			"where"=>$where,
			"fields"=>"id,url,step",
			"order"=>" step ASC,dateline ASC",
			"limit"=>$config['threads']
		));
		if($data){
			foreach($data as $v){
				$urls[]=$v['url'];
				$steps[md5($v['url'])]=$v['step'];
			}
		}else{
			exit('全部完成');
		}
		if($urls){
			M("mod_search_spider")->update(array(
				"islock"=>1,
				"dateline"=>time()
			)," url in("._implode($urls).")");
		}
		 $_SESSION['steps']=$steps;
		$st=microtime(true); 
		$this->loadClass("spider",false,false);
		$this->loadClass("solink",false,false);
		$sp=new Spider();
		/*******采集开始************/ 
		$sp->start($urls,function($rdata){
			$steps=$_SESSION['steps'];
			 
			 echo $rdata['url']."<br>"; 

			  @ob_flush();
			 $solink=new solink($rdata);
			 $solink->get_content($rdata['content']);
			 $title=$solink->get_title();
			 $keywords=$solink->get_keywords();
			 $description=$solink->get_description();
			 $rp=parse_url($rdata['url']);
			 $links=$solink->get_link();
			 /****如果当前页面不存在链接 则直接更新spider*******/
			 if(empty($links)){
				 M("mod_search_spider")->update(array("nolink"=>1),"url='".$rdata['url']."'");
			 }else{
			 if(!$r=M("mod_search_topic")->selectRow("url='".$rdata['url']."'")){
				 
				 M("mod_search_topic")->insert(array(
						"url"=>$rdata['url'],
						"dateline"=>time(),
						"last_time"=>time(),
						"title"=>addslashes($title),
						"keywords"=>addslashes($keywords),
						"description"=>addslashes($description),
						"domain"=>$rp['host'],
						"basedomain"=>getBaseDomain($rp['host'])
				)); 
			 }else{
				 M("mod_search_topic")->update(array(
				 		"last_time"=>time(),
						"title"=>addslashes($title),
						"keywords"=>addslashes($keywords),
						"description"=>addslashes($description),
						"domain"=>$rp['host'],
						"basedomain"=>getBaseDomain($rp['host'])
				),
				"url='".$rdata['url']."'");
			 }
			
			if($links){
				foreach($links as $v){
					$v=trim($v);
					$rh=parse_url($v);
					if(!M("mod_search_spider")->selectRow("url='".$v."' ")){
						M("mod_search_spider")->insert(array(
							"url"=>$v,
							"islock"=>0,
							"dateline"=>time(),
							"step"=>$steps[md5($rdata['url'])]+1,
							"domain"=>$rh['host'],
							"basedomain"=>getBaseDomain($rh['host'])
						));
						
					}
				}
			}
		}
			
		});
		/*******采集结束******/
		 $time=microtime(true)-$st;
		 echo "采集完毕,花费时间".$time;
		 echo '<script>window.location.reload()</script>';
		 
	}
	
	public function onDomain(){
		$domain=get('domain','h');
		if(!$domain){
			exit($domain);
		}
		$row=M("mod_search_domain")->selectRow("domain='".$domain."'");
		if(!$row){
			exit('error');
		}
		if(!$r=M("mod_search_spider")->selectRow("url='".$row['url']."'")){
			M("mod_search_spider")->insert(array(
							"url"=>$row['url'],
							"islock"=>0,
							"domain"=>$domain,
							"dateline"=>time(),
							"step"=>0,
							"domain"=>$row['domain'],
							"basedomain"=>getBaseDomain($row['domain'])
			));
		}else{
			M("mod_search_spider")->update(array(
				"step"=>0,
				"domain"=>$domain,
				"islock"=>0,
				"dateline"=>time()
			),"id=".$r['id']);
			$r=M("mod_search_spider")->selectRow("url='".$row['url']."'");
			 
		}
		
		header("Location: /module.php?m=search_spider&domain=".$domain);
	}
	
	/*解锁某些索引***
	* 比如 0级的 每天解锁 1一级的 每周解锁 2级的每月解锁
	*/
	public function onUnLock(){
		//0级
		$time1=time()-3600*24;
		$time2=time()-3600*24*7;
		$time3=time()-3600*24*30;
		M("mod_search_spider")->update(array("islock"=>0)," step=0 AND dateline<".$time1."");
		M("mod_search_spider")->update(array("islock"=>0)," step=1 AND dateline<".$time2."");
		M("mod_search_spider")->update(array("islock"=>0)," step=1 AND dateline<".$time3."");
		echo "解锁成功";
	}
	
}

?>