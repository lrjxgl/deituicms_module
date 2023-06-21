<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class checkinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$url="/moduleadmin.php?m=checkin&type=".$type;
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_checkin")->select($option,$rscount);
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
					"type"=>$type
				)
			);
			$this->smarty->display("checkin/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$row=MM("checkin","checkin")->where("id=?")->row($id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_checkin")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_checkin")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>