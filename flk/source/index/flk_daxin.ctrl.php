<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class flk_daxinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$daxin=MM("flk","flk_daxin")->get($userid);
			$where=" userid=".$userid;
			$url="/module.php?m=flk_daxin&a=my";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_daxin_log")->select($option,$rscount);
			 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"daxin"=>$daxin
				)
			);
			$this->smarty->display("flk_daxin/index.html");
		}
		
		
	}

?>