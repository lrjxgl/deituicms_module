<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pdd_cbdControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			$where=" 1 ";
			$url="/moduleadmin.php?m=pdd_cbd";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pdd_cbd")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("pdd_cbd/index.html");
		}
		
		public function onAdd(){
			$cbdid=get_post("cbdid","i");
			if($cbdid){
				$data=M("mod_pdd_cbd")->selectRow(array("where"=>"cbdid={$cbdid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pdd_cbd/add.html");
		}
		
		public function onSave(){
			$cbdid=get_post("cbdid","i");
			$data=M("mod_pdd_cbd")->postData();
			if($cbdid){
				M("mod_pdd_cbd")->update($data,"cbdid='$cbdid'");
			}else{
				M("mod_pdd_cbd")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$cbdid=get_post('cbdid',"i");
			$row=M("mod_pdd_cbd")->selectRow("cbdid=".$cbdid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_pdd_cbd")->update(array(
				"status"=>$status
			),"cbdid=".$cbdid);
			
		$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$cbdid=get_post('cbdid',"i");
			M("mod_pdd_cbd")->update(array("status"=>11),"cbdid=$cbdid");
			$this->goall("删除成功",0);
		}
		
		
	}

?>