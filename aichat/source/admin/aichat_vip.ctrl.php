<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_vipControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=aichat_vip&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_vip")->select($option,$rscount);
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
			$this->smarty->display("aichat_vip/index.html");
		}
		
		public function onAdd(){
			$tokenid=get_post("tokenid","i");
			if($tokenid){
				$data=M("mod_aichat_vip")->selectRow(array("where"=>"tokenid=".$tokenid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_vip/add.html");
		}
		
		public function onSave(){
			$tokenid=get_post("tokenid","i");
			$data=M("mod_aichat_vip")->postData();
			if($tokenid){
				M("mod_aichat_vip")->update($data,"tokenid=".$tokenid);
			}else{
				M("mod_aichat_vip")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$tokenid=get_post('tokenid',"i");
			$row=M("mod_aichat_vip")->selectRow("tokenid=".$tokenid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_vip")->update(array("status"=>$status),"tokenid=".$tokenid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$tokenid=get_post('tokenid',"i");
			M("mod_aichat_vip")->update(array("status"=>11),"tokenid=".$tokenid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>