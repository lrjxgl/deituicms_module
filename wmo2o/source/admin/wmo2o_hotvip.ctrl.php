<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class wmo2o_hotvipControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=wmo2o_hotvip&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" vid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_wmo2o_hotvip")->select($option,$rscount);
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
			$this->smarty->display("wmo2o_hotvip/index.html");
		}
		
		public function onAdd(){
			$vid=get_post("vid","i");
			if($vid){
				$data=M("mod_wmo2o_hotvip")->selectRow(array("where"=>"vid=".$vid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("wmo2o_hotvip/add.html");
		}
		
		public function onSave(){
			$vid=get_post("vid","i");
			$data=M("mod_wmo2o_hotvip")->postData();
			if($vid){
				M("mod_wmo2o_hotvip")->update($data,"vid='$vid'");
			}else{
				M("mod_wmo2o_hotvip")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$vid=get_post('vid',"i");
			$status=get_post("status","i");
			M("mod_wmo2o_hotvip")->update(array("status"=>$status),"vid=$vid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$vid=get_post('vid',"i");
			M("mod_wmo2o_hotvip")->update(array("status"=>11),"vid=$vid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>