<?php
class aichat_mappModel extends model{
	public $table="aichat_mapp";
	public function search($keyword){
		return $this->searchSogou($keyword);
	}
	public function searchSogou($keyword){
		$url="https://sogou.com/web?query=".urlencode(trim($keyword))."&_asf=www.sogou.com&_ast=";
		$stole=new stole();
		$stole->getContent($url);
		 
		$stole->cutHtml('<div class="results"');
		//echo $stole->content;
		$arr=$stole->preg_all('<div class="[^>]*space-txt" [^>]*>({content=.*})</div>'); 
		$list=[];
		if(!empty($arr["content"])){
			$i=0;
			foreach($arr["content"] as $rs){
				$i++;
				if($i>5){
					break;
				}
				$list[]=strip_tags($rs);
			}
		}
		return $list;
	}
	public function search360($keyword){
		$url="https://www.so.com/s?ie=utf-8&fr=so.com&src=360sou_newhome&ssid=&sp=ad2&cp=&nlpv=&q=".urlencode(trim($keyword));
		$stole=new stole();
		$stole->getContent($url);
		 
		$stole->cutHtml('<ul class="result">');
		
		$arr=$stole->preg_all('<p class="res-desc">({content=.*})</p>'); 
		$list=[];
		if(!empty($arr["content"])){
			$i=0;
			foreach($arr["content"] as $rs){
				$i++;
				if($i>5){
					break;
				}
				$list[]=strip_tags($rs);
			}
		}
		return $list;
	}
	public function searchBaidu($keyword){
		$url="https://www.baidu.com/s?wd=".urlencode(trim($keyword));
		$stole=new stole();
		$stole->getContent($url);
		 
		$stole->cutHtml('<div id="content_left"');
		
		$arr=$stole->preg_all('<div class="result c-container xpath-log new-pmd"[^>]*>({content=.*})</div>'); 
		$list=[];
		if(!empty($arr["content"])){
			$i=0;
			foreach($arr["content"] as $rs){
				$i++;
				if($i>5){
					break;
				}
				$list[]=strip_tags($rs);
			}
		}
		return $list;
	}
	public function searchBing($keyword){
		$url="https://cn.bing.com/search?q=".urlencode(trim($keyword));
		$stole=new stole();
		$stole->getContent($url);
		$stole->cutHtml('<ol id="b_results" class="">');
		$arr=$stole->preg_all('<p class="b_lineclamp\d+ b_algoSlug">({content=.*})</p>'); 
		$list=[];
		if(!empty($arr["content"])){
			$i=0;
			foreach($arr["content"] as $rs){
				$i++;
				if($i>5){
					break;
				}
				$list[]=strip_tags($rs);
			}
		}
		return $list;
	}
	
}