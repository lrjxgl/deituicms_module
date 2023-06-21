<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsw_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fsw_join&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" joinid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsw_join")->select($option,$rscount);
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
			$this->smarty->display("fsw_join/index.html");
		}
		
		public function onStatus(){
			$joinid=get_post('joinid',"i");
			$row=M("mod_fsw_join")->selectRow("joinid=".$joinid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fsw_join")->update(array("status"=>$status),"joinid=".$joinid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$joinid=get_post('joinid',"i");
			M("mod_fsw_join")->update(array("status"=>11),"joinid=".$joinid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>