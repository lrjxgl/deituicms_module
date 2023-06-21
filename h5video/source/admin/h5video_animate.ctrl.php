<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class h5video_animateControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=h5video_animate&a=default";
			$limit=120;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" animate ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_h5video_animate")->select($option,$rscount);
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
			$this->smarty->display("h5video_animate/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_h5video_animate")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("h5video_animate/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_h5video_animate")->postData();
			if($id){
				M("mod_h5video_animate")->update($data,"id='$id'");
			}else{
				M("mod_h5video_animate")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_h5video_animate")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_h5video_animate")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_h5video_animate")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>