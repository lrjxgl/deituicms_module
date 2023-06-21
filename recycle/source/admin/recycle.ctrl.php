<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class  recycleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where="  status in(0,1,2,3,4)";
			$url="/moduleadmin.php?m=recycle";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_recycle")->select($option,$rscount);
			$statusList=array(
				0=>"未接单",
				1=>"处理中",
				3=>"已完成",
				4=>"已取消"
			);
			if($data){
				foreach($data as $k=>$v){
					$v["status_name"]=$statusList[$v["status"]];
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
			$this->smarty->display("recycle/index.html");
		}
		
		public function onShow(){
			$id=get("id","i");
			 
			$row=MM("recycle","recycle")->selectRow("id=".$id);
			 
			$statusList=MM("recycle","recycle")->statusList();
			$row["status_name"]=$statusList[$row["status"]];
			$logList=M("mod_recycle_log")->select(array(
				"where"=>"recycleid=".$id,
				"order"=>"id ASC"
			));
			$this->smarty->goAssign(array(
				"data"=>$row,
				"logList"=>$logList
			));
			$this->smarty->display("recycle/show.html");
		}
		
		 
		 
		public function onDelete(){
			 
			$id=get_post('id',"i");
			$row=M("mod_recycle")->selectRow("id=".$id);
			 
			if($row["status"]!=0){
				$this->goAll("已接单不能取消",1);
			}
			M("mod_recycle")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>