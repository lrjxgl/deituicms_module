<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bill_shop_userControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=bill_shop_user&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" suid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_shop_user")->select($option,$rscount);
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
			$this->smarty->display("bill_shop_user/index.html");
		}
		
		public function onStatus(){
			$suid=get_post('suid',"i");
			$status=get_post("status","i");
			M("mod_bill_shop_user")->update(array("status"=>$status),"suid=$suid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$suid=get_post('suid',"i");
			M("mod_bill_shop_user")->update(array("status"=>11),"suid=$suid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>