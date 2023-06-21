<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class lltuan_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=lltuan_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_lltuan_place")->select($option,$rscount);
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
			$this->smarty->display("lltuan_place/index.html");
		}
		
		public function onAdd(){
			$placeid=get_post("placeid","i");
			if($placeid){
				$data=M("mod_lltuan_place")->selectRow(array("where"=>"placeid=".$placeid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("lltuan_place/add.html");
		}
		
		public function onSave(){
			$placeid=get_post("placeid","i");
			$data=M("mod_lltuan_place")->postData();
			if($placeid){
				M("mod_lltuan_place")->update($data,"placeid=".$placeid);
			}else{
				M("mod_lltuan_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$placeid=get_post('placeid',"i");
			$row=M("mod_lltuan_place")->selectRow("placeid=".$placeid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_lltuan_place")->update(array("status"=>$status),"placeid=".$placeid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$placeid=get_post('placeid',"i");
			M("mod_lltuan_place")->update(array("status"=>11),"placeid=".$placeid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>