<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class h5video_musicControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=h5video_music&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" musicid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_h5video_music")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$v["url"]=images_site($v["url"]);
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("h5video_music/index.html");
		}
		
		public function onAdd(){
			$musicid=get_post("musicid","i");
			if($musicid){
				$data=M("mod_h5video_music")->selectRow(array("where"=>"musicid=".$musicid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("h5video_music/add.html");
		}
		
		public function onSave(){
			$musicid=get_post("musicid","i");
			$data=M("mod_h5video_music")->postData();
			if($musicid){
				M("mod_h5video_music")->update($data,"musicid='$musicid'");
			}else{
				M("mod_h5video_music")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$musicid=get_post('musicid',"i");
			$row=M("mod_h5video_music")->selectRow("musicid=".$musicid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_h5video_music")->update(array(
				"status"=>$status
			),"musicid=".$musicid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$musicid=get_post('musicid',"i");
			M("mod_h5video_music")->update(array("status"=>11),"musicid=$musicid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>