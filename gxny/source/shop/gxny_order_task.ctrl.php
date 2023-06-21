<?php
class gxny_order_taskControl extends skymvc{
	
	public function onDefault(){
 
		 
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=gxny_order_task";
		$type=get("type","h");
		switch($type){
			case "new":
				$where .=" AND status in(0,1)";
				break;
			case "receive":
				$where.=" AND status=2 ";
				break;
			case "finish":
				$where.=" AND status=3 ";
				break;
			default:
				$where .=" AND status in(0,1,2,3) ";
				break;
		}
		$task_action=get("task_action","h");
		if($task_action){
			$where.=" AND task_action='".$task_action."' ";
		}
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" taskid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_gxny_order_task")->select($option,$rscount);
		 
		if(!empty($data)){
			$shopids=[];
			$statusList=MM("gxny","gxny_order_task")->statusList();
			foreach($data as $k=>$v){
				$shopids[]=$v["shopid"];
			}
			$sps=MM("gxny","gxny_shop")->getListByIds($shopids);
			foreach($data as $k=>$v){
				$v["shopname"]=$sps[$v["shopid"]]["shopname"];
				$v["status_name"]=$statusList[$v["status"]];
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		
		$acList=["种植","施肥","采摘","除草","杀虫"];
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"type"=>$type,
				"catList"=>$catList,
				"acList"=>$acList
			)
		);
		$this->smarty->display("gxny_order_task/index.html");
	}
	public function onFinish(){
		$taskid=get("taskid","i");
		$task=MM("gxny","gxny_order_task")->selectRow("taskid=".$taskid);
		MM("gxny","gxny_order_task")->update(array(
			"updatetime"=>date("Y-m-d H:i:s"),
			"status"=>2
		),"taskid=".$taskid);
		$this->goAll("任务完成");
	}
	
	 
	
}
