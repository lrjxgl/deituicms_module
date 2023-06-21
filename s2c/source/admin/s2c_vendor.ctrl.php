<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class s2c_vendorControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=s2c_vendor&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" vdid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_s2c_vendor")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					 
					$v["yyzz"]=images_site($v["yyzz"]);
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
			$this->smarty->display("s2c_vendor/index.html");
		}
		
		public function onAdd(){
			$vdid=get_post("vdid","i");
			if($vdid){
				$data=M("mod_s2c_vendor")->selectRow(array("where"=>"vdid=".$vdid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("s2c_vendor/add.html");
		}
		
		public function onSave(){
			$vdid=get_post("vdid","i");
			$data=M("mod_s2c_vendor")->postData();
			if($vdid){
				M("mod_s2c_vendor")->update($data,"vdid='$vdid'");
			}else{
				M("mod_s2c_vendor")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$vdid=get_post('vdid',"i");
			$row=M("mod_s2c_vendor")->selectRow("vdid=".$vdid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_s2c_vendor")->update(array(
				"status"=>$status
			),"vdid=".$vdid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$vdid=get_post('vdid',"i");
			M("mod_s2c_vendor")->update(array("status"=>11),"vdid=$vdid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>