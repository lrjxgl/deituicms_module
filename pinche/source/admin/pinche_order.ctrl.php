<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get('type','h');
			$url="/moduleadmin.php?m=pinche_order&type=".$type;
			switch($type){
				case "new":
						$typename="新订单";
						$where=" status=0 AND driverid=0 ";
					break;
				case "send":
						$typename="送客中";
						$where=" status=2";
					break;
				case "finish":
						$typename="已完成";
						$where=" status=3";
					break;
				case "cancel":
						$typename="已取消";
						$where=" status=4";
					break;
				default:
					$typename="全部订单";
					$where=" status in(0,1,2,3,4)";
					break;
				
			}
			 
			$limit=12;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_order")->select($option,$rscount);
			if(!empty($data)){
				$driverids=[];
				$lineids=[];
				$uids=[];
				foreach($data as $v){
					$driverids[]=$v["driverid"];
					$lineids[]=$v["lineid"];
					$uids[]=$v["userid"];
				}
				$drivers=MM("pinche","pinche_driver")->getListByIds($driverids,"driverid,truename");
				$lines=MM("pinche","pinche_line")->getListByIds($lineids,"lineid,title");
				$statusList=MM("pinche","pinche_order")->statusList(); 
				$us=M("user")->getUserByIds($uids,"userid,nickname");
				foreach($data as $k=>$v){
					$v["status_name"]=$statusList[$v["status"]];
					$v["driver"]=$drivers[$v["driverid"]];
					$v["line"]=$lines[$v["lineid"]];
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
					"url"=>$url,
					"typename"=>$typename
				)
			);
			$this->smarty->display("pinche_order/index.html");
		}
		
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			M("mod_pinche_order")->update(array("status"=>$status),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_pinche_order")->update(array("status"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>