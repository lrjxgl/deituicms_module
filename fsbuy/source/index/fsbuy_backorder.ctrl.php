<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsbuy_backorderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onDefault(){
			
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=fsbuy_backorder&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsbuy_backorder")->select($option,$rscount);
			if(!empty($data)){
				foreach($data as $v){
					$ids[]=$v["fsid"];
				}
				$fsbuys=MM("fsbuy","fsbuy")->getListByIds($ids);
				foreach($data as $k=>$v){
					 
					$v['fsbuy']=$fsbuys[$v['fsid']];
				 	$v['timeago']=timeago(strtotime($v['createtime']));
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("fsbuy_backorder/my.html");
		}
		
		 
		
		
	}

?>