<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxlControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fxl&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" fxlid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxl")->select($option,$rscount);
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
			$this->smarty->display("fxl/index.html");
		}
		
		public function onAdd(){
			$fxlid=get_post("fxlid","i");
			if($fxlid){
				$data=M("mod_fxl")->selectRow(array("where"=>"fxlid=".$fxlid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fxl/add.html");
		}
		
		public function onSave(){
			$fxlid=get_post("fxlid","i");
			$data=M("mod_fxl")->postData();
			$data['endtime']=date("Y-m-d H:i:s",$data['endtime']);
			if($fxlid){
				M("mod_fxl")->update($data,"fxlid='$fxlid'");
			}else{
				M("mod_fxl")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$fxlid=get_post('fxlid',"i");
			$status=get_post("status","i");
			M("mod_fxl")->update(array("status"=>$status),"fxlid=$fxlid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$fxlid=get_post('fxlid',"i");
			M("mod_fxl")->update(array("status"=>11),"fxlid=$fxlid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>