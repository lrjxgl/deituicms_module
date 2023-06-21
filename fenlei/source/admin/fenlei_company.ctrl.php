<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fenlei_companyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$status=get("status","i");
			$where=" status=".$status;
			$url="/module.php?m=fenlei_company&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" comid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei_company")->select($option,$rscount);
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
			$this->smarty->display("fenlei_company/index.html");
		}
		
		public function onAdd(){
			$comid=get_post("comid","i");
			if($comid){
				$data=M("mod_fenlei_company")->selectRow(array("where"=>"comid=".$comid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fenlei_company/add.html");
		}
		
		public function onSave(){
			$comid=get_post("comid","i");
			$data=M("mod_fenlei_company")->postData();
			$data["status"]=0;
			if($comid){
				M("mod_fenlei_company")->update($data,"comid='$comid'");
			}else{
				M("mod_fenlei_company")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$comid=get_post('comid',"i");
			$row=M("mod_fenlei_company")->selectRow("comid=".$comid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_fenlei_company")->update(array(
				"status"=>$status
			),"comid=".$comid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$comid=get_post('comid',"i");
			M("mod_fenlei_company")->update(array("status"=>11),"comid=$comid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>