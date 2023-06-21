<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class paotui_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2,3) ";
			$url="/moduleadmin.php?m=paotui_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "new":
					$where.=" AND status=0 ";
					break;
				case "doing":
					$where.=" AND status=1 ";
					break;
				case "check":
					$where.=" AND status=2 ";
					break;
				case "finish":
					$where.=" AND status=3 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_paotui_order")->select($option,$rscount);
			if($data){
				$uids=array();
				$ptids=$sids=array();
				
				foreach($data as $v){
					$uids[]=$v["userid"];
					$sids[]=$v["senderid"];
					$ptids[]=$v["ptid"];
				}
				$us=M("user")->getUserByIds($uids);
				$ss=MM("paotui","paotui_sender")->getListByIds($sids,"senderid,truename");
				$pts=MM("paotui","paotui")->getListByIds($ptids);
			 
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["sender"]=$ss[$v["senderid"]];
					$v["paotui"]=$pts[$v["ptids"]];
					$v["status_name"]=MM("paotui","paotui_order")->getStatus($v["status"]); 
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
			$this->smarty->display("paotui_order/index.html");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_paotui_order")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_paotui_order")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onFineSave(){
			$id=post("id","i");
			$money=post("money","i");
			$order=M("mod_paotui_order")->selectRow("id=".$id);
			$sender=MM("paotui","paotui_sender")->get($order["senderid"]);
			if($sender["money"]<$money){
				$this->goAll("余额不足",1);
			}
			M("mod_paotui_order")->update(array(
				"isfine"=>1,
				"fine_money"=>$money
			),"id=".$id);
			MM("paotui","paotui_sender")->addMoney(array(
				"senderid"=>$order["senderid"],
				"money"=>-$money,
				"content"=>"服务差评，罚款".$money."元"
			));
			$this->goAll("处罚成功");
		}
		
	}

?>