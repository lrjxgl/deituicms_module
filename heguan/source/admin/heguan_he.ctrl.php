<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class heguan_heControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=heguan_he&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" heid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_heguan_he")->select($option,$rscount);
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
			$this->smarty->display("heguan_he/index.html");
		}
		
		public function onAdd(){
			$heid=get_post("heid","i");
			if($heid){
				$data=M("mod_heguan_he")->selectRow(array("where"=>"heid=".$heid));
				$imgsdata=parseImgsData($data["imgsdata"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("heguan_he/add.html");
		}
		
		public function onSave(){
			$heid=get_post("heid","i");
			$data=M("mod_heguan_he")->postData();
			if($heid){
				M("mod_heguan_he")->update($data,"heid=".$heid);
			}else{
				M("mod_heguan_he")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$heid=get_post('heid',"i");
			$row=M("mod_heguan_he")->selectRow("heid=".$heid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_heguan_he")->update(array("status"=>$status),"heid=".$heid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$heid=get_post('heid',"i");
			M("mod_heguan_he")->update(array("status"=>11),"heid=".$heid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>