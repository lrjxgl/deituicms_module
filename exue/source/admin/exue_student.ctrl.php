<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_studentControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=exue_student&a=default";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND truename like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" stid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_student")->select($option,$rscount);
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
					"keyword"=>$keyword
				)
			);
			$this->smarty->display("exue_student/index.html");
		}
		
		public function onAdd(){
			$stid=get_post("stid","i");
			if($stid){
				$data=M("mod_exue_student")->selectRow(array("where"=>"stid=".$stid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exue_student/add.html");
		}
		
		public function onSave(){
			$stid=get_post("stid","i");
			$data=M("mod_exue_student")->postData();
			if($stid){
				M("mod_exue_student")->update($data,"stid='$stid'");
			}else{
				M("mod_exue_student")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$stid=get_post('stid',"i");
			$status=get_post("status","i");
			M("mod_exue_student")->update(array("status"=>$status),"stid=$stid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$stid=get_post('stid',"i");
			M("mod_exue_student")->update(array("status"=>11),"stid=$stid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>