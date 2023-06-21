<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class f2c_teamControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=f2c_team&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" teamid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_f2c_team")->select($option,$rscount);
			if($data){
				 
				foreach($data as $k=>$v){
					$v["userhead"]=images_site($v["userhead"]);
					 
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
			$this->smarty->display("f2c_team/index.html");
		}
		
		public function onAdd(){
			$teamid=get_post("teamid","i");
			if($teamid){
				$data=M("mod_f2c_team")->selectRow(array("where"=>"teamid=".$teamid));
				
			}
			 
			$this->smarty->goassign(array(
				"data"=>$data,
			 
			));
			$this->smarty->display("f2c_team/add.html");
		}
		
		public function onSave(){
			$teamid=get_post("teamid","i");
			$data=M("mod_f2c_team")->postData();
			if($teamid){
				M("mod_f2c_team")->update($data,"teamid='$teamid'");
			}else{
				M("mod_f2c_team")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$teamid=get_post('teamid',"i");
			$row=M("mod_f2c_team")->selectRow("teamid=".$teamid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_f2c_team")->update(array(
				"status"=>$status
			),"teamid=".$teamid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$teamid=get_post('teamid',"i");
			M("mod_f2c_team")->update(array("status"=>11),"teamid=$teamid");
			$this->goAll("删除成功");
			 
		}
		 
		
	}

?>