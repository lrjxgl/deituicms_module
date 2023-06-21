<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sblog_topicControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=sblog_topic&a=default";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$isindex=get("isindex","i");
			if($isindex){
				$where.=' AND isindex='.$isindex;
				$url.="&isindex=1";
			}
			$isrecommend=get("isrecommend","i");
			if($isrecommend){
				$where.=" AND isrecommend=1 ";
				$url.="&isrecommend=1";
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
			$data=M("mod_sblog_topic")->select($option,$rscount);
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
					"keyword"=>$keyword
				)
			);
			$this->smarty->display("sblog_topic/index.html");
		}
		public function onAdd(){
			$id=get_post('id',"i");
			$data=M("mod_sblog_topic")->selectRow("id=".$id);
			$this->smarty->goAssign(array(
				"data"=>$data
			));
			$this->smarty->display("sblog_topic/add.html");
		}
		public function onSave(){
			$id=get_post('id',"i");
			$data=M("mod_sblog_topic")->postData();
			M("mod_sblog_topic")->update($data,"id=".$id);
			$this->goAll("保存成功");
		}
		public function onStatus(){
			$id=get_post('id',"i");
			$status=1;
			$row=M("mod_sblog_topic")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}
			M("mod_sblog_topic")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_sblog_topic")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		public function onIndex(){
			$id=get_post('id',"i");
			$status=1;
			$row=M("mod_sblog_topic")->selectRow("id=".$id);
			if($row["isindex"]==1){
				$status=0;
			}
			M("mod_sblog_topic")->update(array("isindex"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$status=1;
			$row=M("mod_sblog_topic")->selectRow("id=".$id);
			if($row["isrecommend"]==1){
				$status=0;
			}
			M("mod_sblog_topic")->update(array("isrecommend"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		
	}

?>