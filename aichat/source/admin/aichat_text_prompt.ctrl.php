<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_text_promptControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=aichat_text_prompt&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" promptid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_text_prompt")->select($option,$rscount);
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
			$this->smarty->display("aichat_text_prompt/index.html");
		}
		
		public function onAdd(){
			$promptid=get_post("promptid","i");
			if($promptid){
				$data=M("mod_aichat_text_prompt")->selectRow(array("where"=>"promptid".$promptid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_text_prompt/add.html");
		}
		
		public function onSave(){
			$promptid=get_post("promptid","i");
			$data=M("mod_aichat_text_prompt")->postData();
			if($promptid){
				M("mod_aichat_text_prompt")->update($data,"promptid=".$promptid);
			}else{
				M("mod_aichat_text_prompt")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$promptid=get_post('promptid',"i");
			$row=M("mod_aichat_text_prompt")->selectRow("promptid=".$promptid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_text_prompt")->update(array("status"=>$status),"promptid=".$promptid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$promptid=get_post('promptid',"i");
			M("mod_aichat_text_prompt")->update(array("status"=>11),"promptid=".$promptid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>