<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_teacherControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="shopid=".SHOPID." AND status in(0,1,2)";
			$url="/moduleshop.php?m=exue_teacher&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tcid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_teacher")->Dselect($option,$rscount);
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
			$this->smarty->display("exue_teacher/index.html");
		}
		
		public function onAdd(){
			$tcid=get_post("tcid","i");
			if($tcid){
				$data=M("mod_exue_teacher")->selectRow(array("where"=>"tcid=".$tcid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exue_teacher/add.html");
		}
		
		public function onSave(){
			$tcid=get_post("tcid","i");
			$data=M("mod_exue_teacher")->postData();
			
			if($tcid){
				M("mod_exue_teacher")->update($data,"tcid='$tcid'");
			}else{
				$data["shopid"]=SHOPID;
				M("mod_exue_teacher")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$tcid=get_post('tcid',"i");
			$row=M("mod_exue_teacher")->selectRow("tcid=".$tcid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_exue_teacher")->update(array(
				"status"=>$status
			),"tcid=".$tcid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$tcid=get_post('tcid',"i");
			M("mod_exue_teacher")->update(array("status"=>11),"tcid=$tcid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>