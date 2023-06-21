<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_ppControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "online":
					$where=" status=1 ";
					break;
				case "offline":
					$where=" status=2 ";
					break;
				default:
					$where=" status in(0,1,2)";
					break;
			}
			
			$url="/moduleadmin.php?m=zbtao_pp&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ppid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_pp")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_pp/index.html");
		}
		
		public function onAdd(){
			$ppid=get_post("ppid","i");
			if($ppid){
				$data=M("mod_zbtao_pp")->selectRow(array("where"=>"ppid=".$ppid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zbtao_pp/add.html");
		}
		
		public function onSave(){
			$ppid=get_post("ppid","i");
			$data=M("mod_zbtao_pp")->postData();
			if($ppid){
				M("mod_zbtao_pp")->update($data,"ppid='$ppid'");
			}else{
				M("mod_zbtao_pp")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$ppid=get_post('ppid',"i");
			 
			$row=M("mod_zbtao_pp")->selectRow("ppid=".$ppid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_zbtao_pp")->update(array("status"=>$status),"ppid=$ppid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$ppid=get_post('ppid',"i");
			 
			$row=M("mod_zbtao_pp")->selectRow("ppid=".$ppid);
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_zbtao_pp")->update(array("isrecommend"=>$status),"ppid=$ppid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$ppid=get_post('ppid',"i");
			M("mod_zbtao_pp")->update(array("status"=>11),"ppid=$ppid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>