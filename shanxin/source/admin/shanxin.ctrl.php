<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class shanxinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="moduleadmin.php?m=shanxin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" sid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_shanxin")->select($option,$rscount);
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
			$this->smarty->display("shanxin/index.html");
		}
		
		public function onAdd(){
			$sid=get_post("sid","i");
			if($sid){
				$data=M("mod_shanxin")->selectRow(array("where"=>"sid=".$sid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("shanxin/add.html");
		}
		
		public function onSave(){
			$sid=get_post("sid","i");
			$data=M("mod_shanxin")->postData();
			$data["stime"]=strtotime($data["stime"]);
			$data["etime"]=strtotime($data["etime"]);
			if($sid){
				M("mod_shanxin")->update($data,"sid='$sid'");
			}else{
				M("mod_shanxin")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$sid=get_post('sid',"i");
			$status=get_post("status","i");
			M("mod_shanxin")->update(array("status"=>$status),"sid=$sid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$sid=get_post('sid',"i");
			M("mod_shanxin")->update(array("status"=>11),"sid=$sid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>