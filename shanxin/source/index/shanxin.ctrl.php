<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shanxinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="module.php?m=shanxin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" sid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shanxin")->select($option,$rscount);
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
			$this->smarty->display("shanxin/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="module.php?m=shanxin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" sid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shanxin")->select($option,$rscount);
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
			$this->smarty->display("shanxin/index.html");
		}
		
		public function onShow(){
			$sid=get_post("sid","i");
			$shanxin=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
			$this->smarty->goassign(array(
				"shanxin"=>$shanxin
			));
			$this->smarty->display("shanxin/show.html");
		}
		
	}

?>