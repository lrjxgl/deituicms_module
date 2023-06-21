<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class imgdiy_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=imgdiy_category&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" catid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_imgdiy_category")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("imgdiy_category/index.html");
		}
		
		public function onAdd(){
			$catid=get_post("catid","i");
			if($catid){
				$data=M("mod_imgdiy_category")->selectRow(array("where"=>"catid={$catid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("imgdiy_category/add.html");
		}
		
		public function onSave(){
			$catid=get_post("catid","i");
			$data=M("mod_imgdiy_category")->postData();
			if($catid){
				M("mod_imgdiy_category")->update($data,"catid='$catid'");
			}else{
				M("mod_imgdiy_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$catid=get_post('catid',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_imgdiy_category")->update(array("bstatus"=>$bstatus),"catid=$catid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			M("mod_imgdiy_category")->update(array("bstatus"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>