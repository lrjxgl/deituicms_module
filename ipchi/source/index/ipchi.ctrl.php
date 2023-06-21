<?php
class ipchiControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("ipchi/index.html");
	}
	public function onInit(){
		session_write_close();
	}
	//检验ip是否有效
	public function onCheck(){
		set_time_limit(0);
		//header('Content-Encoding: identity', true);
		ob_implicit_flush(1);
		echo "正在验证ip..";
		$order=" updatetime ASC";
		$where=" status in (0,1,2)";
		if(get("new")){
			$order=" createtime DESC";
			$where=" status=0 ";
		} 
		for($i=0;$i<100000;$i++){
			$row=M("mod_ipchi_ip")->selectRow(array(
				"where"=>$where,
				"order"=>$order
			)); 
			if(empty($row)){
				echo "处理成功";
				exit;
			}
			M("mod_ipchi_ip")->update(array(
				"updatetime"=>date("Y-m-d H:i:s")
			),"id=".$row["id"]);
			$url="http://www.fd175.com/1.htm";
			 
			$stole=new stole();
			$proxy=$row["ip"].":".$row["port"];
			$stole->getContent($url,$proxy,1);

			if($stole->content==1){
				echo $proxy."代理可用<br/>";
				M("mod_ipchi_ip")->update(array(
					"updatetime"=>date("Y-m-d H:i:s"),
					"usetime"=>date("Y-m-d H:i:s"),
					"status"=>1
				),"id=".$row["id"]);
			}else{
				M("mod_ipchi_ip")->update(array(
					"updatetime"=>date("Y-m-d H:i:s"),
					"status"=>2
				),"id=".$row["id"]);
				echo $proxy."代理不可用<br/>";
			}
			//清楚内存
			unset($row);
			unset($stole);
			echo str_repeat("
			 
			", 1024);
			ob_flush();
			flush();
			ob_end_flush();
		}
	}
	public function onzdaye(){
		$url="http://www.zdaye.com/";
		$snum=0;
		for($page=1;$page<2;$page++){
			if($page==1){
				$url="https://www.zdaye.com/dayProxy/ip/334127.html";
			}else{
				$url="http://www.kxdaili.com/1/".$page.".html";
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<tbody>');
			 
			preg_match_all("/<tr>.*<td>(.*)<\/td>.*<td>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			 
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					$ip=$arr[1][$k];
					preg_match("/([^<]*)>.*/iUs",$ip,$p);
					$ip=trim($p[1]);
					$port=intval($arr[2][$k]);
					exit($ip.":".$port);
					$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
					if(empty($row)){
						$snum++;
						M("mod_ipchi_ip")->insert(array(
							"ip"=>$ip,
							"port"=>$port,
							"area"=>"zh",
							"createtime"=>date("Y-m-d H:i:s"),
							"updatetime"=>date("Y-m-d H:i:s"),
							"usetime"=>date("Y-m-d H:i:s"),
							"protocol"=>"http"
						));
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	
	public function on66(){
		$snum=0;
		for($page=1;$page<10;$page++){
			if($page==1){
				$url="http://www.66ip.cn/index.html";
			}else{
				$url="http://www.66ip.cn/".$page.".html";
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<table width=\'100%\' border="2px" cellspacing="0px" bordercolor="#6699ff">');
			 
			preg_match_all("/<tr>.*<td>(.*)<\/td>.*<td>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			print_r($arr);
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					if($k>0){
						$ip=$arr[1][$k];
						$port=$arr[2][$k];
						$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
						if(empty($row)){
							$snum++;
							M("mod_ipchi_ip")->insert(array(
								"ip"=>$ip,
								"port"=>$port,
								"area"=>"zh",
								"createtime"=>date("Y-m-d H:i:s"),
								"updatetime"=>date("Y-m-d H:i:s"),
								"usetime"=>date("Y-m-d H:i:s"),
								"protocol"=>"http"
							));
						}
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	public function on662(){
		$url="http://www.66ip.cn/mo.php?sxb=&tqsl=100&port=&export=&ktip=&sxa=&submit=%CC%E1++%C8%A1&textarea=";
		$stole=new stole();
		$stole->getContent($url);
		preg_match_all("/([\d]+\.[\d]+\.[\d]+.[\d]+:[\d]+)<br \/>/iUs",$stole->content,$arr);
		if(!empty($arr)){
			foreach($arr[1] as $k=>$v){
				$e=explode(":",$arr[1][$k]);
				$ip=$e[0];
				$port=$e[1];
				$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
				if(empty($row)){
					M("mod_ipchi_ip")->insert(array(
						"ip"=>$ip,
						"port"=>$port,
						"area"=>"zh",
						"createtime"=>date("Y-m-d H:i:s"),
						"updatetime"=>date("Y-m-d H:i:s"),
						"usetime"=>date("Y-m-d H:i:s"),
						"protocol"=>"http"
					));
				}
			}
		}
		echo "采集成功".count($arr[1])."个";
	}
	//开心代理
	public function onkxdaili(){
		$snum=0;
		for($page=1;$page<10;$page++){
			if($page==1){
				$url="http://www.kxdaili.com/dailiip.html";
			}else{
				$url="http://www.kxdaili.com/1/".$page.".html";
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<tbody>');
			 
			preg_match_all("/<tr>.*<td>(.*)<\/td>.*<td>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			 
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					$ip=$arr[1][$k];
					$port=$arr[2][$k];
					$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
					if(empty($row)){
						$snum++;
						M("mod_ipchi_ip")->insert(array(
							"ip"=>$ip,
							"port"=>$port,
							"area"=>"zh",
							"createtime"=>date("Y-m-d H:i:s"),
							"updatetime"=>date("Y-m-d H:i:s"),
							"usetime"=>date("Y-m-d H:i:s"),
							"protocol"=>"http"
						));
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	
	//快代理
	public function onkuaidaili(){
		$snum=0;
		for($page=1;$page<5;$page++){
			if($page==1){
				$url="https://www.kuaidaili.com/free/";
			}else{
				$url="https://www.kuaidaili.com/free/inha/".$page."/";
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<tbody>');
			 
			preg_match_all("/<tr>.*<td.*>(.*)<\/td>.*<td.*>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			 
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					$ip=$arr[1][$k];
					$port=$arr[2][$k];
					$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
					if(empty($row)){
						$snum++;
						M("mod_ipchi_ip")->insert(array(
							"ip"=>$ip,
							"port"=>$port,
							"area"=>"zh",
							"createtime"=>date("Y-m-d H:i:s"),
							"updatetime"=>date("Y-m-d H:i:s"),
							"usetime"=>date("Y-m-d H:i:s"),
							"protocol"=>"http"
						));
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	
	public function onfatezero(){
		$snum=0;
		$url="http://proxylist.fatezero.org/proxy.list";
		$c=file_get_contents($url);
		$ex=explode("\n",$c);
		if(!empty($ex)){
			foreach($ex as $ee){
				$arr=json_decode($ee,true);
				
				if(empty($arr)) continue;
				$ip=$arr['host'];
				$port=$arr['port'];
				$area=$arr["country"];
				$protocol=$arr['type'];
				$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
				if(empty($row)){
					$snum++;
					M("mod_ipchi_ip")->insert(array(
						"ip"=>$ip,
						"port"=>$port,
						"area"=>$area,
						"createtime"=>date("Y-m-d H:i:s"),
						"updatetime"=>date("Y-m-d H:i:s"),
						"usetime"=>date("Y-m-d H:i:s"),
						"protocol"=>$protocol
					));
				}
			}
		} 
		echo "采集成功".$snum."个";
		
	}
	
	
	//云代理
	public function onip3366(){
		$snum=0;
		for($page=1;$page<5;$page++){
			if($page==1){
				$url="http://www.ip3366.net/free/?stype=1";
			}else{
				$url="http://www.ip3366.net/free/?stype=1&page=".$page;
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<tbody>');
			 
			preg_match_all("/<tr>.*<td.*>(.*)<\/td>.*<td.*>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			 
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					$ip=$arr[1][$k];
					$port=$arr[2][$k];
					$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
					if(empty($row)){
						$snum++;
						M("mod_ipchi_ip")->insert(array(
							"ip"=>$ip,
							"port"=>$port,
							"area"=>"zh",
							"createtime"=>date("Y-m-d H:i:s"),
							"updatetime"=>date("Y-m-d H:i:s"),
							"usetime"=>date("Y-m-d H:i:s"),
							"protocol"=>"http"
						));
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	
	
	//89ip
	public function on89ip(){
		$snum=0;
		for($page=1;$page<5;$page++){
			if($page==1){
				$url="https://www.89ip.cn/index_1.html";
			}else{
				$url="https://www.89ip.cn/index_".$page.".html";
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<tbody>');
			 
			preg_match_all("/<tr>.*<td.*>(.*)<\/td>.*<td.*>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			/*print_r($arr);
			 exit;
			*/ 
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					$ip=trim($arr[1][$k]);
					$port=trim($arr[2][$k]);
					$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
					if(empty($row)){
						$snum++;
						M("mod_ipchi_ip")->insert(array(
							"ip"=>$ip,
							"port"=>$port,
							"area"=>"zh",
							"createtime"=>date("Y-m-d H:i:s"),
							"updatetime"=>date("Y-m-d H:i:s"),
							"usetime"=>date("Y-m-d H:i:s"),
							"protocol"=>"http"
						));
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	
	//国外https://pzzqz.com/
	
	public function onpzzqz(){
		$snum=0;
		for($page=1;$page<5;$page++){
			if($page==1){
				$url="https://pzzqz.com/index_1.html";
			}else{
				$url="https://pzzqz.com/index_".$page.".html";
			}
			$stole=new stole();
			$stole->getContent($url);
			//echo $stole->content;
			$c=$stole->cutHtml('<tbody>');
			 
			preg_match_all("/<tr>.*<td.*>(.*)<\/td>.*<td.*>(.*)<\/td>.*<\/tr>/iUs",$c,$arr);
			/*print_r($arr);
			 exit;
			*/ 
			if(!empty($arr)){
				foreach($arr[1] as $k=>$v){
					$ip=trim($arr[1][$k]);
					$port=trim($arr[2][$k]);
					$row=M("mod_ipchi_ip")->selectRow("ip='".$ip."' AND port=".$port." ");
					if(empty($row)){
						$snum++;
						M("mod_ipchi_ip")->insert(array(
							"ip"=>$ip,
							"port"=>$port,
							"area"=>"zh",
							"createtime"=>date("Y-m-d H:i:s"),
							"updatetime"=>date("Y-m-d H:i:s"),
							"usetime"=>date("Y-m-d H:i:s"),
							"protocol"=>"http"
						));
					}
				}
			}
		}
		echo "采集成功".$snum."个";
	}
	
}
?>