<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cj1_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3,4) AND iswin=1 ";
			$url="/moduleadmin.php?m=cj1_order";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cj1_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
					$ids[]=$v['objectid'];
				}
				$us=M("user")->getUserByIds($uids);
				$cjs=MM("cj1","mod_cj1")->getListByIds($ids);
				 
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['title']=$cjs[$v['objectid']]['title'];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("cj1_order/index.html");
		}
		
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			M("mod_cj1_order")->update(array("status"=>$status),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_cj1_order")->update(array("status"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		public function onUse(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			$row=M("mod_cj1_order")->selectRow("orderid=".$orderid);
			if($row["isuse"]){
				$this->goAll("已使用了",1);
			} 
			M("mod_cj1_order")->update(array(
				"isuse"=>1,
				"use_time"=>time()
				
			),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		
	}

?>