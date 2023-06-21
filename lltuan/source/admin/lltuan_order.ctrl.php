<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class lltuan_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=lltuan_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				case "doing":
					$where=" status in(1,2) ";
					break;
				case "finish":
					$where=" status=3 ";
					break;
				default:
					$where=" status in(0,1,2,3,4)";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_lltuan_order")->select($option,$rscount);
			if(!empty($data)){
				$gids=[];
				foreach($data as $v){
					$gids[]=$v["gid"];
				}
				$gs=MM("lltuan","lltuan_group")->getListByIds($gids);
				foreach($data as $k=>$v){
					$v["group"]=$gs[$v["gid"]];
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
			$this->smarty->display("lltuan_order/index.html");
		}
		
		 
		
		public function onPass(){
			$orderid=get_post('orderid',"i");
			$row=M("mod_lltuan_order")->selectRow("orderid=".$orderid);
			if($row["status"]!=0){
				$this->goAll("已经审核过了",1);
			}
			M("mod_lltuan_order")->update(array("status"=>1),"orderid=".$orderid);
			MM("lltuan","lltuan_group")->changenum("join_num",$row["num"],"gid=".$row["gid"]);
			$this->goall("审核成功",0,$status);
		}
		
		public function onForbid(){
			$orderid=get_post('orderid',"i");
			$row=M("mod_lltuan_order")->selectRow("orderid=".$orderid);
			if($row["status"]!=0){
				$this->goAll("已经审核过了",1);
			}
			M("mod_lltuan_order")->update(array("status"=>4),"orderid=".$orderid);
			 
			$this->goall("取消成功",0,$status);
		}
		
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$row=M("mod_lltuan_order")->selectRow("orderid=".$orderid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_lltuan_order")->update(array("status"=>$status),"orderid=".$orderid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_lltuan_order")->update(array("status"=>11),"orderid=".$orderid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>