<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_lineControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=pinche_line&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" lineid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_line")->select($option,$rscount);
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
			$this->smarty->display("pinche_line/index.html");
		}
		
		public function onAdd(){
			$lineid=get_post("lineid","i");
			if($lineid){
				$data=M("mod_pinche_line")->selectRow(array("where"=>"lineid=".$lineid));
				
			}
		 
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pinche_line/add.html");
		}
		
		public function onSave(){
			$lineid=get_post("lineid","i");
			$data=M("mod_pinche_line")->postData();
			
			if($lineid){
				M("mod_pinche_line")->update($data,"lineid='$lineid'");
			}else{
				M("mod_pinche_line")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$lineid=get_post('lineid',"i");
			$row=M("mod_pinche_line")->selectRow("lineid=".$lineid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_pinche_line")->update(array("status"=>$status),"lineid=$lineid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$lineid=get_post('lineid',"i");
			M("mod_pinche_line")->update(array("status"=>11),"lineid=$lineid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>