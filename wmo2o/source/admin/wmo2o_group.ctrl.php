<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class wmo2o_groupControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=wmo2o_group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_wmo2o_group")->select($option,$rscount);
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
			$this->smarty->display("wmo2o_group/index.html");
		}
		
		public function onAdd(){
			$gid=get_post("gid","i");
			if($gid){
				$data=M("mod_wmo2o_group")->selectRow(array("where"=>"gid=".$gid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("wmo2o_group/add.html");
		}
		
		public function onSave(){
			$gid=get_post("gid","i");
			$data=M("mod_wmo2o_group")->postData();
			$data["status"]=1;
			if($gid){
				M("mod_wmo2o_group")->update($data,"gid='$gid'");
			}else{
				M("mod_wmo2o_group")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$gid=get_post('gid',"i");
			$status=get_post("status","i");
			M("mod_wmo2o_group")->update(array("status"=>$status),"gid=$gid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$gid=get_post('gid',"i");
			M("mod_wmo2o_group")->update(array("status"=>11),"gid=$gid");
			$this->goAll("删除成功");
			 
		}
		
		public function onClear(){
			$gid=get_post('gid',"i");
			M("mod_wmo2o_group_product")->delete("gid=".$gid);
			$this->goAll("清空成功");
		}
		
	}

?>