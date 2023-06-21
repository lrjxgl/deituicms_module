<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsw_matchControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fsw_match&a=default";
			$type=get("type","h");
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" mid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_match")->Dselect($option,$rscount);
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
			$this->smarty->display("fsw_match/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fsw_match&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" mid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsw_match")->select($option,$rscount);
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
			$this->smarty->display("fsw_match/index.html");
		}
		
		public function onShow(){
			$mid=get_post("mid","i");
			$data=M("mod_fsw_match")->selectRow(array("where"=>"mid=".$mid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fsw_match/show.html");
		}
		public function onAdd(){
			$mid=get_post("mid","i");
			if($mid){
				$data=M("mod_fsw_match")->selectRow(array("where"=>"mid=".$mid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fsw_match/add.html");
		}
		
		public function onSave(){
			$mid=get_post("mid","i");
			$data=M("mod_fsw_match")->postData();
			if($mid){
				M("mod_fsw_match")->update($data,"mid=".$mid);
			}else{
				M("mod_fsw_match")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$mid=get_post('mid',"i");
			$row=M("mod_fsw_match")->selectRow("mid=".$mid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fsw_match")->update(array("status"=>$status),"mid=".$mid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$mid=get_post('mid',"i");
			M("mod_fsw_match")->update(array("status"=>11),"mid=".$mid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>