<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ttcj_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3) ";
			$url="/moduleadmin.php?m=ttcj_order&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$list=M("mod_ttcj_order")->select($option,$rscount);
			if(!empty($list)){
				foreach($list as $v){
					$cjids[]=$v["cjid"];
				}
				$cjs=MM("ttcj","ttcj")->getListByIds($cjids);
				$statusList=MM("ttcj","ttcj_order")->statusList();
				foreach($list as $k=>$v){
					$v["title"]=$cjs[$v["cjid"]]["title"];
					$v["status_name"]=$statusList[$v["status"]];
					$list[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("ttcj_order/index.html");
		}
		
		 
		
		public function onSend(){
			$orderid=get("orderid","i");
			$order=M("mod_ttcj_order")->selectRow("orderid=".$orderid);
			M("mod_ttcj_order")->update(array(
				"status"=>1
			),"orderid=".$orderid);
			$this->goAll("发送成功");
		}
		
		public function onFinish(){
			$orderid=get("orderid","i");
			$order=M("mod_ttcj_order")->selectRow("orderid=".$orderid);
			M("mod_ttcj_order")->update(array(
				"status"=>3
			),"orderid=".$orderid);
			$this->goAll("订单完成");
		}
		
	}

?>