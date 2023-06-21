<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class collect_ruleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();			 
		}
		
		public function onDefault(){
			$where=" 1=1 ";
			$url="/moduleadmin.php?m=collect_rule&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_collect_rule")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$r=MM("collect","collect_category")->get($v["mdname"],"catid=".$v["catid"]);
					$v['cname']=$r["title"];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->assign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"type_list"=>MM("collect","collect_type")->id_title()
				)
			);
			$this->smarty->display("collect_rule/index.html");
		}
		
 		public function onCopy(){
			$id=get_post('id','i');
			$data=M("mod_collect_rule")->selectRow(array("where"=>"id={$id}"));
			unset($data['id']);
			$data['title']=$data['title']."副本";
			M("mod_collect_rule")->insert($data);	
			$this->goall("复制成功");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_collect_rule")->selectRow(array("where"=>"id={$id}"));				
			}
			$this->smarty->assign(array(
				"data"=>$data,
				"catlist"=>MM("collect","collect_category")->children($data["mdname"]) ,
				"type_list"=>MM("collect","collect_type")->id_title(1),
				"moduleList"=>MM("collect","collect_rule")->moduleList()
			));
			$this->smarty->display("collect_rule/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$data["title"]=post("title","h");
			$data["dateline"]=time();
			$data["type_id"]=post("type_id","i");
			$data["domain"]=post("domain","h");
			$data["list_url"]=post("list_url","x");
			$data["list_rule"]=post("list_rule","a");
			$data["content_url"]=post("content_url","x");
			$data["content_rule"]=post("content_rule","a");
			$data['start_page']=post('start_page','i');
			$data['end_page']=post('end_page','i');
			$data['page_content']=post('page_content','h');
			$data['catid']=post('catid','i');
			$data['page_url']=post('page_url','x');
			$data['dl_url']=post('dl_url','x');
			$data['filter_img']=post('filter_img','i');
			$data['remote_img']=post('remote_img','i');
			$data['now_page']=post('now_page','i');
			$data['pagesize']=post('pagesize','i');
			$data["iswap"]=post("iswap","i");
			$data["mdname"]=post("mdname","h");
			$data["iscurl"]=post("iscurl","i");
			if($id){
				M("mod_collect_rule")->update($data,"id='$id'");
			}else{
				
				M("mod_collect_rule")->insert($data);
			}
			$this->goall("保存成功");
		}
		/*清空未采集内容*/
		public function onClear(){
			$rule_id=get('id','i');
			
			m('collect')->delete("rule_id=".$rule_id." AND status=0");
			$this->goall("清空成功",0);
		}
		
		public function onimport(){
			//规则导入
			if(post('content')){
				$data=json_decode(stripslashes(post('content')),true);
				unset($data['id']);
				$data['title'].="_import";
				$id=M("mod_collect_rule")->insert($data);
				$this->goall("导入成功",0,0,"/moduleadmin.php?m=collect_rule&a=add&id=$id");
			}
			$this->smarty->display("collect_rule/import.html");
		}
		public function onshare(){
			//导出
			$id=get_post("id","i");
			$data=M("mod_collect_rule")->selectRow("id=$id");
			$this->smarty->assign(array(
				"content"=>json_encode($data)
			));
			$this->smarty->display("collect_rule/share.html");
		}
		
		public function onTestList(){
			header("Content-type:text/html;charset=utf8;");
			$this->loadClass("stole");
			$this->stole->iswap=get_post("iswap","i");
			$this->stole->isCurl=get_post("iscurl","i");
			$config=M("mod_collect_config")->selectRow("1");
			if($config["isproxy"]){
				$dip=MM("collect","collect_ip")->getIp();
				$this->stole->getContent(get_post('list_url'),$dip);
			}else{
				$this->stole->getContent(get_post('list_url'));
			}
			
			 
			$a=explode("\r\n",stripslashes(get_post('list_rule'))); 
			$replace_1=$replace_2=array();	
			foreach($a as $k=>$v){
				
				if(!empty($v)){
					$t_d=explode("=>>",$v);	
					if($t_d[0]!=="replace"){		
						$arr=$this->parseRule($t_d[0],$t_d[1]);
					}else{
						$a=explode("=>",$t_d[1]);
						$replace_1[]=$a[0];
						$replace_2[]=$a[1];
					}
				}
			}
		 
			if(isset($arr['url']) && is_array($arr['url']) && !empty($arr['url'])){
				foreach($arr['url'] as $k=>$v){
					$arr['url'][$k]=str_replace($replace_1,$replace_2,$this->builtlink($v,get_post('domain','h')));
				}
			}
			print_r($arr);
		}
		
		public function builtlink($url,$domain=""){
			if(substr($url,0,2)=="//"){
				$http=substr($domain,0,5)=="https"?"https:":"http:";
				return $http.$url;
			}elseif($url[0]=="/"){
				return $domain.$this->host.$url;
			}elseif(preg_match("/^http/i",$url)){
				return $url;
			}
			return $url;
		}
		
		public function onTestContent(){
			$this->loadClass("stole");
			$this->stole->iswap=get_post("iswap","i");
			$this->stole->isCurl=get_post("iscurl","i");
			$this->stole->getContent(get_post('content_url'));
			
			$a=explode("\r\n",stripslashes(get_post('content_rule')));  		
			foreach($a as $k=>$v){
				if(!empty($v)){
					$t_d=explode("=>>",$v);		
					$arr=$this->parseRule($t_d[0],$t_d[1]);
				}
			}
			
			$newdata=array(); 
			if(is_array($arr)){
				$content=$arr['content'][0];
				foreach($arr as $k=>$v){
					$newdata[$k]=$v[0];
				}
			}else{
				$content=$arr;
				$newdata["content"]=$content;
			}
			if(get_post('filter_img')){
				preg_match_all("/<img.*src=['\"](.*)['\"][^>]*/iUs",$content,$a);
				if(isset($a[1]) && !empty($a[1])){
					$content="";
					foreach($a[1] as $v){
						$content.="<img src='".$v."'>"; 
					}
				}
			}
			 
			print_r($newdata);
		
		}
		
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_collect_rule")->update(array("status"=>$status),"id=$id");
			exit(json_encode(array("error"=>0,"message"=>"状态修改成功")));
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_collect_rule")->delete("id={$id}");
			exit(json_encode(array("error"=>0,"message"=>"删除成功")));
		}
		
		public function parseRule($t,$rule){
			switch($t){
					case "c":
							return $this->stole->cutHtml($rule);
						break;
					case "a":
							return $this->stole->preg_all($rule);
						break;
					case "r":
							return $this->stole->removeHtml($rule);
						break;
					case "rp":
							return $this->stole->removePreg($rule);
						break;
					
			}
		}
		
		public function onCatList(){
			$tablename=get("tablename","h");
			$data=MM("collect","collect_category")->children($tablename);
			$this->goAll("success",0,$data);
		} 
		
		
	}

?>