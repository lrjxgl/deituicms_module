<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_paotui_lmshopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="moduleshop.php?m=csc_paotui_lmshop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" lmid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_paotui_lmshop")->select($option,$rscount);
			
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
			$this->smarty->display("csc_paotui_lmshop/index.html");
		}
		
		public function onAdd(){
			$lmid=get_post("lmid","i");
			if($lmid){
				$data=M("mod_csc_paotui_lmshop")->selectRow(array("where"=>"lmid=".$lmid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("csc_paotui_lmshop/add.html");
		}
		
		public function onSave(){
			$lmid=get_post("lmid","i");
			$data=M("mod_csc_paotui_lmshop")->postData();
			$data["shopid"]=SHOPID;
			if($lmid){
				M("mod_csc_paotui_lmshop")->update($data,"lmid='$lmid'");
			}else{
				M("mod_csc_paotui_lmshop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$lmid=get_post('lmid',"i");
			$status=get_post("status","i");
			M("mod_csc_paotui_lmshop")->update(array("status"=>$status),"lmid=$lmid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$lmid=get_post('lmid',"i");
			M("mod_csc_paotui_lmshop")->update(array("status"=>11),"lmid=$lmid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>