<?php
class test_ipControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$proxy="118.187.58.35:53281";
		echo $this->curl_get_contents("https://www.deituicms.com/iptest.html",$proxy);
	}
	
	public function onGetIps(){
		session_write_close();
		set_time_limit(0);
		$ipdata=array();
		for($page=1;$page<30;$page++){
			$c=file_get_contents("https://www.xicidaili.com/nt/{$page}");
			preg_match("/<table[^>]*>(.*)<\/table>/iUs",$c,$arr);
			$table=$arr[1];
			$table=str_replace("</td>",":",$table);
			$table=str_replace("<td>",":",$table);
			preg_match_all("/([\d]+\.[\d]+\.[\d]+\.[\d]+):[^:]*:([\d]+):/iUs",$table,$barr);
			if(isset($barr[1])){
				foreach($barr[1] as $k=>$ip){
					if(isset($barr[2][$k])){
						$ipdata[]=$ip.":".$barr[2][$k];
					}
					
				}
			}
			
		}
		echo implode("<br/>",$ipdata);
		 
		
	}
	
	function curl_get_contents($url,$proxy=""){
		$ch=curl_init();
		if(!empty($proxy)){
			$p=explode(":",$proxy);
			curl_setopt ($ch, CURLOPT_PROXY, $p[0]);
			$port=isset($p[1])?html($p[1]):"80";
			curl_setopt($ch, CURLOPT_PROXYPORT,$port);  
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1"); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$c=curl_exec($ch);
		curl_close($ch);
		return $c;
	}
	
}
?>