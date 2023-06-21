<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jdo2o_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=jdo2o_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jdo2o_place")->select($option,$rscount);
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
			$this->smarty->display("jdo2o_place/index.html");
		}
		
		public function onAdd(){
			$placeid=get_post("placeid","i");
			if($placeid){
				$data=M("mod_jdo2o_place")->selectRow(array("where"=>"placeid=".$placeid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("jdo2o_place/add.html");
		}
		
		public function onSave(){
			$placeid=get_post("placeid","i");
			$data=M("mod_jdo2o_place")->postData();
			if($placeid){
				M("mod_jdo2o_place")->update($data,"placeid='$placeid'");
			}else{
				M("mod_jdo2o_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$placeid=get_post('placeid',"i");
			$status=get_post("status","i");
			M("mod_jdo2o_place")->update(array("status"=>$status),"placeid=$placeid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$placeid=get_post('placeid',"i");
			M("mod_jdo2o_place")->update(array("status"=>11),"placeid=$placeid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>