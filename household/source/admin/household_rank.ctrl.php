<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class household_rankControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=household_rank&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" rankid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_household_rank")->select($option,$rscount);
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
			$this->smarty->display("household_rank/index.html");
		}
		
		public function onAdd(){
			$rankid=get_post("rankid","i");
			if($rankid){
				$data=M("mod_household_rank")->selectRow(array("where"=>"rankid=".$rankid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("household_rank/add.html");
		}
		
		public function onSave(){
			$rankid=get_post("rankid","i");
			$data=M("mod_household_rank")->postData();
			if($rankid){
				M("mod_household_rank")->update($data,"rankid='$rankid'");
			}else{
				M("mod_household_rank")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$rankid=get_post('rankid',"i");
			$status=get_post("status","i");
			M("mod_household_rank")->update(array("status"=>$status),"rankid=$rankid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$rankid=get_post('rankid',"i");
			M("mod_household_rank")->update(array("status"=>11),"rankid=$rankid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>