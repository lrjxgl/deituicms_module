<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_agent_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=house_agent_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_agent_apply")->select($option,$rscount);
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
			$this->smarty->display("house_agent_apply/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_house_agent_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_agent_apply")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		public function onPass(){
			$id=get_post('id',"i");
			$status=1;
			M("mod_house_agent_apply")->update(array("status"=>$status),"id=$id");
			$_POST=M("mod_house_agent_apply")->selectRow("id=".$id);
			$data=M("mod_house_agent")->postData();
			M("mod_house_agent")->insert($data);
			$this->goall("审核通过");
		}
		public function onForbid(){
			$id=get_post('id',"i");
			$status=4;
			M("mod_house_agent_apply")->update(array("status"=>$status),"id=$id");
			$this->goall("禁止成功");
		}
		
	}

?>