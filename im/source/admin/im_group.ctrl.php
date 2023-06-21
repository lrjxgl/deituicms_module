<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class im_groupControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=im_group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" groupid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_im_group")->select($option,$rscount);
			$typeList=MM("im","im_group")->typeList();
			if($data){
				foreach($data as $k=>$v){
					$v["typeid_title"]=$typeList[$v["typeid"]]["title"];
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
			$this->smarty->display("im_group/index.html");
		}
		
		public function onAdd(){
			$groupid=get_post("groupid","i");
			if($groupid){
				$data=MM("im","im_group")->selectRow(array("where"=>"groupid=".$groupid));
				
			}
			$typeList=MM("im","im_group")->typeList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"typeList"=>$typeList
			));
			$this->smarty->display("im_group/add.html");
		}
		
		public function onSave(){
			$groupid=get_post("groupid","i");
			$data=M("mod_im_group")->postData();
			if($groupid){
				M("mod_im_group")->update($data,"groupid='$groupid'");
			}else{
				M("mod_im_group")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$groupid=get_post('groupid',"i");
			$row=M("mod_im_group")->selectRow("groupid=".$groupid);
			if($row["status"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_im_group")->update(array("status"=>$status),"groupid=$groupid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$groupid=get_post('groupid',"i");
			M("mod_im_group")->update(array("status"=>11),"groupid=$groupid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>