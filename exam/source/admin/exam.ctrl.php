<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class examControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=exam&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" exid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exam")->select($option,$rscount);
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
			$this->smarty->display("exam/index.html");
		}
		
		public function onAdd(){
			$exid=get_post("exid","i");
			if($exid){
				$data=M("mod_exam")->selectRow(array("where"=>"exid=".$exid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exam/add.html");
		}
		
		public function onSave(){
			$exid=get_post("exid","i");
			$data=M("mod_exam")->postData();
			if($exid){
				M("mod_exam")->update($data,"exid='$exid'");
			}else{
				M("mod_exam")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$exid=get_post('exid',"i");
			$status=1;
			$row=M("mod_exam")->selectRow("exid=$exid");
			if($row["status"]==1){
				$status=2;
			}
			M("mod_exam")->update(array("status"=>$status),"exid=$exid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$exid=get_post('exid',"i");
			M("mod_exam")->update(array("status"=>11),"exid=$exid");
			$this->goAll("删除成功");
			 
		}
		public function onOnline(){
			$exid=get_post('exid',"i");
			$status=1;
			$row=M("mod_exam")->selectRow("exid=$exid");
			if($row["isonline"]==1){
				$status=0;
			} 
			M("mod_exam")->update(array("isonline"=>$status),"exid=$exid");
			$this->goAll("发布成功",0,$status);
		}
		
	}

?>