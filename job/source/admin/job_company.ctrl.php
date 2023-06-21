<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class job_companyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$url="/module.php?m=job_company&type=".$type;
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" comid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_job_company")->select($option,$rscount);
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
			$this->smarty->display("job_company/index.html");
		}
		
		public function onAdd(){
			$comid=get_post("comid","i");
			if($comid){
				$data=M("mod_job_company")->selectRow(array("where"=>"comid=".$comid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("job_company/add.html");
		}
		
		public function onSave(){
			$comid=get_post("comid","i");
			$data=M("mod_job_company")->postData();
			if($comid){
				M("mod_job_company")->update($data,"comid='$comid'");
			}else{
				M("mod_job_company")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$comid=get_post('comid',"i");
			$row=M("mod_job_company")->selectRow("comid=".$comid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_job_company")->update(array("status"=>$status),"comid=".$comid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$comid=get_post('comid',"i");
			M("mod_job_company")->update(array("status"=>11),"comid=$comid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>