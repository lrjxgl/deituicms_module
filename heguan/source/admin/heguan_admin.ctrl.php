<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class heguan_adminControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=heguan_admin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" adminid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_heguan_admin")->select($option,$rscount);
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
			$this->smarty->display("heguan_admin/index.html");
		}
		
		public function onAdd(){
			$adminid=get_post("adminid","i");
			if($adminid){
				$data=M("mod_heguan_admin")->selectRow(array("where"=>"adminid=".$adminid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("heguan_admin/add.html");
		}
		
		public function onSave(){
			$adminid=get_post("adminid","i");
			$data=M("mod_heguan_admin")->postData();
			if($adminid){
				M("mod_heguan_admin")->update($data,"adminid=".$adminid);
			}else{
				M("mod_heguan_admin")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$adminid=get_post('adminid',"i");
			$row=M("mod_heguan_admin")->selectRow("adminid=".$adminid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_heguan_admin")->update(array("status"=>$status),"adminid=".$adminid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$adminid=get_post('adminid',"i");
			M("mod_heguan_admin")->update(array("status"=>11),"adminid=".$adminid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>