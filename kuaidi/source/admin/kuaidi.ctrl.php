<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class kuaidiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onInit(){
			$key="mdsq".MODULENAME;
			if(!$data=cache()->setType("file")->get($key)){
				$domain=getBaseDomain($_SERVER['HTTP_HOST']);
				$json=file_get_contents("http://www.deitui.com/index.php?m=yunmodule&a=domain&domain=$domain&appdir=".MODULENAME);
				$data=json_decode($json,true);
				if(empty($data)) return true;
				if(!$data['error']){
					cache()->setType("file")->set($key,$data,3600);
				}		
			}
			if($data['error']){				
				exit("当前域名未授权，不可使用");
			}
		}
		
		public function onMenu(){
			$this->smarty->display("kuaidi/menu.html");
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=kuaidi&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_kuaidi")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("kuaidi/index.html");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_kuaidi")->delete("id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>