<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_place_tagControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fishing_place_tag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_place_tag")->select($option,$rscount);
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
			$this->smarty->display("fishing_place_tag/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fishing_place_tag")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_fishing_place_tag")->where("id=?")->row($id);
			
			M("mod_fishing_place_tag")->update(array("status"=>11),"id=$id");
			//更新钓点标签
			$tags=M("mod_fishing_place_tag")->selectCols(array(
				"where"=>" placeid=".$row["placeid"]." AND status in(0,1) ",
				"order"=>"grade DESC",
				"limit"=>5,
				"fields"=>"title"
			));
			$tagcon="";
			if(!empty($tags)){
				$tagcon=implode(" ",$tags);
			}
			M("mod_fishing_place")->update(array(
				"tags"=>$tagcon
			),"placeid=".$row["placeid"]);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>