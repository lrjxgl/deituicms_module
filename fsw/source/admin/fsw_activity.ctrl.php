<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsw_activityControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fsw_activity&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_activity")->Dselect($option,$rscount);
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
			$this->smarty->display("fsw_activity/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fsw_activity&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsw_activity")->select($option,$rscount);
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
			$this->smarty->display("fsw_activity/index.html");
		}
		
		public function onShow(){
			$actid=get_post("actid","i");
			$data=M("mod_fsw_activity")->selectRow(array("where"=>"actid=".$actid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fsw_activity/show.html");
		}
		public function onAdd(){
			$actid=get_post("actid","i");
			if($actid){
				$data=M("mod_fsw_activity")->selectRow(array("where"=>"actid=".$actid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fsw_activity/add.html");
		}
		
		public function onSave(){
			$actid=get_post("actid","i");
			$data=M("mod_fsw_activity")->postData();
			if($actid){
				M("mod_fsw_activity")->update($data,"actid=".$actid);
			}else{
				M("mod_fsw_activity")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$actid=get_post('actid',"i");
			$row=M("mod_fsw_activity")->selectRow("actid=".$actid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fsw_activity")->update(array("status"=>$status),"actid=".$actid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$actid=get_post('actid',"i");
			M("mod_fsw_activity")->update(array("status"=>11),"actid=".$actid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>