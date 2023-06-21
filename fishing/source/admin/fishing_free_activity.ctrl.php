<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_activityControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fishing_free_activity&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_free_activity")->select($option,$rscount);
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
			$this->smarty->display("fishing_free_activity/index.html");
		}
		
		public function onAdd(){
			$actid=get_post("actid","i");
			$placeid=get_post("placeid","i");
			if($actid){
				$data=M("mod_fishing_free_activity")->selectRow(array("where"=>"actid=".$actid));
				$placeid=$data["placeid"];
			}
			$place=M("mod_fishing_free_place")->selectRow("placeid=".$placeid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"place"=>$place
			));
			$this->smarty->display("fishing_free_activity/add.html");
		}
		
		public function onSave(){
			$actid=get_post("actid","i");
			$data=M("mod_fishing_free_activity")->postData();
			if($actid){
				M("mod_fishing_free_activity")->update($data,"actid=".$actid);
			}else{
				M("mod_fishing_free_activity")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$actid=get_post('actid',"i");
			$row=M("mod_fishing_free_activity")->selectRow("actid=".$actid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fishing_free_activity")->update(array("status"=>$status),"actid=".$actid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$actid=get_post('actid',"i");
			M("mod_fishing_free_activity")->update(array("status"=>11),"actid=".$actid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>