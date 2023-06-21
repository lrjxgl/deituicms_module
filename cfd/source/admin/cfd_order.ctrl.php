<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cfd_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3) ";
			$type=get("type","h");
			$url="/moduleadmin.php?m=cfd_order&type=".$type;
			$cfdid=get("cfdid","i");
			if($where){
				$where.=" AND cfdid=".$cfdid;
				$url.="&cfdid=".$cfdid;
			}
			$limit=20;
			$start=get("per_page","i");
			switch($type){
				case "new":
					$where=" status=0 AND ispay=1  ";
					$typename="新订单";
					break;
				case "forbid":
					$where=" status=4 ";
					$typename="已取消";
					break;
				case "finish":
					$where=" status=3 AND isreward=1 ";
					$typename="已完成";
					break;
				case "unpay":
					$where=" status=0 AND ispay=0 ";
					$typename="待支付";
					break;
				default:
					$typename="全部";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cfd_order")->select($option,$rscount);
			if($data){
				$uids=$cfdids=array();
				foreach($data as $v){
					$uids[]=$v["userid"];
					$cfdids[]=$v["cfdid"];
					$rids[]=$v["rewardid"];
				}
				$us=M("user")->getUserByIds($uids,"userid,nickname");
				$cfds=MM("cfd","cfd")->getListByIds($cfdids,"cfdid,title");
				$rws=MM("cfd","cfd_reward")->getListByIds($rids,"id,title"); 
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["title"]=$cfds[$v["cfdid"]]["title"];
					$v["reward_title"]=$rws[$v["rewardid"]]["title"];
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
					"type"=>$type,
					"typename"=>$typename
				)
			);
			$this->smarty->display("cfd_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_cfd_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("cfd_order/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$status=get_post("status","i");
			M("mod_cfd_order")->update(array("status"=>$status),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_cfd_order")->update(array("status"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>