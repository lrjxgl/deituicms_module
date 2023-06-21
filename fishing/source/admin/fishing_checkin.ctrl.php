<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_checkinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$url="/moduleadmin.php?m=fishing_checkin&type=".$type;
			if($type=='check'){
				$where=" status=0 ";
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
			$data=MM("fishing","fishing_checkin")->Dselect($option,$rscount);
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
			if($type=="check"){
				$this->smarty->display("fishing_checkin/check.html");
			}else{
				$this->smarty->display("fishing_checkin/index.html");
			}
			
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fishing_checkin")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_checkin/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_fishing_checkin")->postData();
			if($id){
				M("mod_fishing_checkin")->update($data,"id='$id'");
			}else{
				M("mod_fishing_checkin")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fishing_checkin")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fishing_checkin")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			M("mod_fishing_checkin")->update(array("status"=>1),"id=".$id);
			$this->goAll("审核通过");
		}
		
		public function onForbid(){
			$id=get_post('id',"i");
			M("mod_fishing_checkin")->update(array("status"=>2),"id=".$id);
			$this->goAll("审核不通过");
		}
		
	}

?>