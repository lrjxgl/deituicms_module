<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class collect_ipControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "all":
				 
					$where=" status in(0,1,3) ";
					break;
				default:
					$where=" status=1 ";
					break;
			}
			 
			$ip=get("ip","h");
			if($ip){
				$where.=" AND ip='".$ip."' ";
			}
			$url="/moduleadmin.php?m=collect_ip&type=".$type;
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
		 
			$data=M("mod_collect_ip")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"type"=>$type,
					"url"=>$url
				)
			);
			$this->smarty->display("collect_ip/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_collect_ip")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("collect_ip/add.html");
		}
		
		public function onSave(){
			 
			$ips=explode("\r\n",post("ips","h"));
			foreach($ips as $ip){
				$ip=sql(html(trim($ip)));
				if(empty($ip)) continue;
				$row=M("mod_collect_ip")->selectRow("ip='".$ip."'");
				if(!$row){
					M("mod_collect_ip")->insert(array(
						"ip"=>$ip
					));
				}
			}
			 
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_collect_ip")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_collect_ip")->delete("id=$id");
			$this->goAll("删除成功");
			 
		}
		public function onTestip(){
			session_write_close();
			set_time_limit(0);
		 
			switch(get("type")){
				case "yes":
					$where=" status=1 ";
					break;
				case "new":
					$where=" status=0 ";
					break;
				default:
					$where=" status in(0,3) ";
					break;
			}
			$res=M("mod_collect_ip")->select(array(
				"where"=>$where,
				"limit"=>2000,
				"order"=>"checktime ASC"
			));
			 
			ob_implicit_flush(true);
			if($res){
				foreach($res as $rs){
					
					echo "正在验证".$rs["ip"];
					echo $res=$this->curl_get_contents("https://www.deituicms.com/iptest.html",$rs["ip"]);
					if($res=="ip=1"){
						M("mod_collect_ip")->update(array(
							"status"=>1,
							"checktime"=>time(),
							"updatetime"=>time()
						),"id=".$rs["id"]);
						echo "yes";
					}else{
						M("mod_collect_ip")->update(array(
							"status"=>3,
							"checktime"=>time(),
							"updatetime"=>time()
						),"id=".$rs["id"]);
						echo " no";
					}
					echo "<br/>";
					ob_flush();
					ob_end_flush();
				}
			}
			
			echo "ip 检测完毕";
		}
		function curl_get_contents($url,$proxy=""){
			$ch=curl_init();
			if(!empty($proxy)){
				$p=explode(":",$proxy);
				curl_setopt ($ch, CURLOPT_PROXY, $p[0]);
				$port=isset($p[1])?html($p[1]):"80";
				curl_setopt($ch, CURLOPT_PROXYPORT,$port);  
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
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