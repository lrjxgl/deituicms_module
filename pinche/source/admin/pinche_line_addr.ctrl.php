<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_line_addrControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=pinche_line_addr&a=default";
			$lineid=get("lineid","i");
			$stype=get("stype","i");
			if($lineid){
				$where.=" AND lineid=".$lineid;
			}
			if($stype){
				$where.=" AND stype=".$stype;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" addrid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_line_addr")->select($option,$rscount);
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
			$this->smarty->display("pinche_line_addr/index.html");
		}
		
		public function onAdd(){
			$addrid=get_post("addrid","i");
			if($addrid){
				$data=M("mod_pinche_line_addr")->selectRow(array("where"=>"addrid=".$addrid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pinche_line_addr/add.html");
		}
		
		public function onLine(){
			$lineid=get("lineid","i");
			$stype=get("stype","i");
			$line=M("mod_pinche_line")->selectRow("lineid=".$lineid);
			$this->smarty->goAssign(array(
				"line"=>$line,
				"stype"=>$stype
			));
			$this->smarty->display("pinche_line_addr/line.html");
		}
		public function onSave(){
			$addrid=get_post("addrid","i");
			$data=M("mod_pinche_line_addr")->postData();
			if(empty($data["addr"])){
				$this->goAll("名称不能为空",1);
			}
			if($data["lat"]==0){
				$this->goAll("地理位置不能为空",1);
			}
			if($addrid){
				M("mod_pinche_line_addr")->update($data,"addrid='$addrid'");
			}else{
				M("mod_pinche_line_addr")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$addrid=get_post('addrid',"i");
			$status=get_post("status","i");
			M("mod_pinche_line_addr")->update(array("status"=>$status),"addrid=$addrid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$addrid=get_post('addrid',"i");
			M("mod_pinche_line_addr")->update(array("status"=>11),"addrid=$addrid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>