<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class paotui_addrControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		
		public function onDefault(){
			$userid=M("login")->userid;
			$type=get("type","h");
			if($type=="online"){
				$where=" userid=".$userid." AND status=1 ";
			}else{
				$where=" userid=".$userid." AND status in(0,1,2)";
			}
			
			$url="/module.php?m=paotui_addr&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" addrid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_paotui_addr")->select($option,$rscount);
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
			$this->smarty->display("paotui_addr/index.html");
		}
		
		public function onAdd(){
			$addrid=get_post("addrid","i");
			if($addrid){
				$data=M("mod_paotui_addr")->selectRow(array("where"=>"addrid=".$addrid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("paotui_addr/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$addrid=get_post("addrid","i");
			$data=M("mod_paotui_addr")->postData();
			$data["status"]=1;
			if($addrid){
				$row=M("mod_paotui_addr")->selectRow("addrid='$addrid'");
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_paotui_addr")->update($data,"addrid='$addrid'");
			}else{
				$data["userid"]=$userid;
				M("mod_paotui_addr")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$addrid=get_post('addrid',"i");
			$status=get_post("status","i");
			$userid=M("login")->userid;
			$row=M("mod_paotui_addr")->selectRow("addrid='$addrid'");
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_paotui_addr")->update(array("status"=>$status),"addrid=$addrid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$addrid=get_post('addrid',"i");
			$status=11;
			$userid=M("login")->userid;
			$row=M("mod_paotui_addr")->selectRow("addrid='$addrid'");
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_paotui_addr")->update(array("status"=>$status),"addrid=$addrid");
			$this->goall("删除成功");
		}
		
	}

?>