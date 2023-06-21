<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxl_certControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fxl_cert&a=default";
			$fxlid=get_post("fxlid","i");
			$fxl=M("mod_fxl")->selectRow(array("where"=>"fxlid=".$fxlid));
			if($fxlid){
				$where.=" AND fxlid=".$fxlid;
				$url.="&fxlid=".$fxlid;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxl_cert")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"fxl"=>$fxl
				)
			);
			$this->smarty->display("fxl_cert/index.html");
		}
		
		public function onAdd(){
			
			
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fxl_cert")->selectRow(array("where"=>"id=".$id));
				$fxlid=$data["fxlid"];
			}else{
				$fxlid=get_post("fxlid","i");
				$fxl=M("mod_fxl")->selectRow(array("where"=>"fxlid=".$fxlid));
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"fxl"=>$fxl,
				"fxlid"=>$fxlid
			));
			$this->smarty->display("fxl_cert/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_fxl_cert")->postData();
			if($id){
				M("mod_fxl_cert")->update($data,"id='$id'");
			}else{
				M("mod_fxl_cert")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fxl_cert")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fxl_cert")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>