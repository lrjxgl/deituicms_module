<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_groupControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get('type','h');
			$url="/moduleadmin.php?m=pinche_group&type=".$type;
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
			 
			
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_group")->select($option,$rscount);
			if(!empty($data)){
				$driverids=[];
				$lineids=[];
				foreach($data as $v){
					$driverids[]=$v["driverid"];
					$lineids[]=$v["lineid"];
				}
				$drivers=MM("pinche","pinche_driver")->getListByIds($driverids,"driverid,truename");
				$lines=MM("pinche","pinche_line")->getListByIds($lineids,"lineid,title");
				$statusList=MM("pinche","pinche_group")->statusList(); 
				foreach($data as $k=>$v){
					$v["status_name"]=$statusList[$v["status"]];
					$v["driver"]=$drivers[$v["driverid"]];
					$v["line"]=$lines[$v["lineid"]];
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
			$this->smarty->display("pinche_group/index.html");
		}
		
		public function onShow(){
			$gid=get("gid","i");
			$group=M("mod_pinche_group")->selectRow("gid=".$gid);
			 
			$group["wait_etime_fmt"]=date("H:i",$group["wait_etime"]);
			$statusList=MM("pinche","pinche_group")->statusList();
			$group["status_name"]=$statusList[$group["status"]];
			$line=M("mod_pinche_line")->selectRow("lineid=".$group["lineid"]);
			$list=MM("pinche","pinche_order")->Dselect(array(
				"where"=>" gid=".$gid
			));
			$this->smarty->goAssign(array(
				"group"=>$group,
				"line"=>$line,
				"list"=>$list
			));
			$this->smarty->display("pinche_group/show.html");
		}
		
		public function onDelete(){
			$gid=get_post('gid',"i");
			M("mod_pinche_group")->update(array("status"=>11),"gid=$gid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>