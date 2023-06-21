<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_courseControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID." AND status in(0,1,2)";
			$url="/moduleshop.php?m=exue_course&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" courseid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("exue","exue_course")->Dselect($option,$rscount);
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
			$this->smarty->display("exue_course/index.html");
		}
		
		public function onAdd(){
			$courseid=get_post("courseid","i");
			if($courseid){
				$data=M("mod_exue_course")->selectRow(array("where"=>"courseid=".$courseid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			$catList=MM("exue","exue_category")->children(0);
			$this->smarty->goassign(array(
				"data"=>$data,
				"catList"=>$catList,
			));
			$this->smarty->display("exue_course/add.html");
		}
		
		public function onSave(){
			$courseid=get_post("courseid","i");
			$data=M("mod_exue_course")->postData();
			if($courseid){
				unset($data["stype"]);
				M("mod_exue_course")->update($data,"courseid='$courseid'");
			}else{
				$data["shopid"]=SHOPID;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_exue_course")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$courseid=get_post('courseid',"i");
			$status=get_post("status","i");
			M("mod_exue_course")->update(array("status"=>$status),"courseid=$courseid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$courseid=get_post('courseid',"i");
			M("mod_exue_course")->update(array("status"=>11),"courseid=$courseid");
			$this->goAll("删除成功");
			 
		}
		
		
		
	}

?>