<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_shop_studentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="shopid=".SHOPID." AND status in(0,1,2)";
			$url="/moduleshop.php?m=exue_shop_student&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" updateTime DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_shop_student")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("exue_shop_student/index.html");
		}
		
		 
		
		 
		public function onDelete(){
			$tcid=get_post('tcid',"i");
			M("mod_exue_shop_student")->update(array("status"=>11),"tcid=$tcid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>