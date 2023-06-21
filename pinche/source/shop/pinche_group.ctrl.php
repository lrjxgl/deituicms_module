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
			$where=" driverid=".DRIVERID." AND status in(0,1,2,3,4) ";
			$url="/moduleshop.php?m=pinche_group&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("pinche","pinche_group")->Dselect($option,$rscount);
			 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data?$data:[],
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("pinche_group/index.html");
		}
		
		public function onNew(){
			$od=M("mod_pinche_group")->selectRow("driverid=".DRIVERID." AND status<3 ");
			if($od){
				$this->goAll("您有拼车订单未完成，不能接单",3);
			}
			$lineids=M("mod_pinche_driver_line")->selectCols(array(
				"where"=>" driverid=".DRIVERID." AND status=1 ",
				"fields"=>"lineid "
			));
			if(empty($lineids)){
				$this->goAll("暂无线路设置",2);
			}
			$etime=time()+180;
			$where=" status=0 AND driverid=0  AND wait_etime<".$etime." AND lineid in("._implode($lineids).") ";
			
			$url="/moduleshop.php?m=pinche_group&a=new";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" gid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("pinche","pinche_group")->Dselect($option,$rscount);
		 
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data?$data:[],
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("pinche_group/new.html");
		}
		
		public function onShow(){
			$gid=get("gid","i");
			$group=M("mod_pinche_group")->selectRow("gid=".$gid);
			if($group["driverid"]!=DRIVERID){
				$this->goAll("暂无权限",1);
			}
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
		 
		
		public function onCancel(){
			$gid=get("gid","i");
			$group=MM("pinche","pinche_group")->selectRow("gid=".$gid);
			if($group["driverid"]!=DRIVERID){
				$this->goAll("暂无权限",1);
			}
			if($group["status"]>1){
				$this->goAll("处理过了",1);
			}
			//检测乘客订单状态
			$od=MM("pinche","pinche_order")->selectRow("gid=".$gid." AND status=3  ");
			if(!empty($od)){
				$this->goAll("有乘客订单完成，无法取消",1);
			}
			MM("pinche","pinche_group")->begin();
			M("mod_pinche_group_log")->insert(array(
				"gid"=>$gid,
				"role"=>"driver",
				"roleid"=>DRIVERID,
				"action"=>"cancel",
				"dateline"=>time(),
				"status"=>1,
				"content"=>"司机取消了订单"
			));
			//检测是否可以重新分配
			if($group["wait_etime"]<time()){
				//直接取消
				MM("pinche","pinche_group")->update(array("status"=>4),"gid=".$gid);
				//通知所有乘客
				$oList=MM("pinche","pinche_order")->select("gid=".$gid);
				foreach($oList as $u){
					
					MM("pinche","pinche_order")->cancel($u["orderid"],$u);
				}
			}else{
				//取消接单者 重新分配
				MM("pinche","pinche_group")->update(array("status"=>0,"driverid"=>0),"gid=".$gid);
			}
			
			
			
			
			MM("pinche","pinche_group")->commit();
			$this->goAll("取消成功");
			 
		}
		
		public function onConfirm(){
			$gid=get("gid","i");
			$group=MM("pinche","pinche_group")->selectRow("gid=".$gid);
			if($group["driverid"]!=DRIVERID){
				$this->goAll("暂无权限",1);
			}
			if($group["status"]>=2){
				$this->goAll("处理过了",1);
			}
			MM("pinche","pinche_group")->update(array("status"=>2),"gid=".$gid);
			M("mod_pinche_group_log")->insert(array(
				"gid"=>$gid,
				"role"=>"driver",
				"roleid"=>DRIVERID,
				"action"=>"confirm",
				"dateline"=>time(),
				"status"=>1,
				"content"=>"司机确认接单，正在接乘客上车"
			));
			MM("pinche","pinche_order")->update(array("status"=>1),"gid=".$gid); 
			$this->goAll("操作成功");
			 
		}
		
		
		public function onFinish(){
			$gid=get("gid","i");
			$group=MM("pinche","pinche_group")->selectRow("gid=".$gid);
			if($group["driverid"]!=DRIVERID){
				$this->goAll("暂无权限",1);
			}
			if($group["status"]>2){
				$this->goAll("处理过了",1);
			}
			//检测乘客订单状态
			$od=MM("pinche","pinche_order")->selectRow("gid=".$gid." AND status in(0,1,2) ");
			if(!empty($od)){
				$this->goAll("有乘客未下车，无法完成",1);
			}
			MM("pinche","pinche_group")->update(array("status"=>3),"gid=".$gid);
			M("mod_pinche_group_log")->insert(array(
				"gid"=>$gid,
				"role"=>"driver",
				"roleid"=>DRIVERID,
				"action"=>"finish",
				"dateline"=>time(),
				"status"=>1,
				"content"=>"司机接送完成"
			));
			MM("group","pinche_group")->finish($gid,$group); 
			$this->goAll("操作成功");
			 
		}
		
		public function OnGrabOrder(){
			$gid=get("gid","i");
			$row=M("mod_pinche_group")->selectRow("gid=".$gid);
			if($row["driverid"]){
				$this->goAll("已经被抢走了",1);
			}
			//限制司机接单
			$od=M("mod_pinche_group")->selectRow("driverid=".DRIVERID." AND status<3 ");
			if($od){
				$this->goAll("您有拼车订单未完成，不能接单",1);
			}
			M("mod_pinche_group")->update(array(
				"driverid"=>DRIVERID
			),"gid=".$gid);
			M("mod_pinche_order")->update(array(
				"driverid"=>DRIVERID
			),"gid=".$gid);
			M("mod_pinche_group_log")->insert(array(
				"gid"=>$gid,
				"role"=>"driver",
				"roleid"=>DRIVERID,
				"action"=>"grab",
				"dateline"=>time(),
				"status"=>1,
				"content"=>"司机抢单成功"
			));
			$this->goAll("抢单成功");
		}
		
		
	}

?>