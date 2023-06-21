<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class group_utagControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=group_utag&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_group_utag")->select($option,$rscount);
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
			$this->smarty->display("group_utag/index.html");
		}
		
		public function onAdd(){
			$tagid=get_post("tagid","i");
			if($tagid){
				$data=M("mod_group_utag")->selectRow(array("where"=>"tagid=".$tagid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("group_utag/add.html");
		}
		
		public function onSave(){
			$tagid=get_post("tagid","i");
			$data=M("mod_group_utag")->postData();
			if($tagid){
				M("mod_group_utag")->update($data,"tagid=".$tagid);
			}else{
				M("mod_group_utag")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$tagid=get_post('tagid',"i");
			$row=M("mod_group_utag")->selectRow("tagid=".$tagid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_group_utag")->update(array("status"=>$status),"tagid=".$tagid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$tagid=get_post('tagid',"i");
			M("mod_group_utag")->update(array("status"=>11),"tagid=".$tagid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>