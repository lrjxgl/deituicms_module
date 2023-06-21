<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mscardControl extends skymvc{
		
		public function __construct(){
			parent::__construct();			 
		}
		
		public function onInit(){
			M("login")->checkLogin();
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=mscard&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mscard")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$shopids[]=$v['shopid'];
				}
				$shops=M("shop")->getShopByIds($shopids);
				foreach($data as $k=>$v){
					$v['shopname']=$shops[$v['shopid']]['shopname'];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("mscard/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_mscard")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("mscard/show.html");
		}
		
	}

?>