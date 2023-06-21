<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class dzp_eventControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=dzp_event&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" eventid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_dzp_event")->select($option,$rscount);
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
			$this->smarty->display("dzp_event/index.html");
		}
		
		public function onAdd(){
			$eventid=get_post("eventid","i");
			if($eventid){
				$data=M("mod_dzp_event")->selectRow(array("where"=>"eventid=".$eventid));
				
			}
			$datetime=date("Y-m-d H:i:s");
			$this->smarty->goassign(array(
				"data"=>$data,
				"datetime"=>$datetime
			));
			$this->smarty->display("dzp_event/add.html");
		}
		
		public function onSave(){
			$eventid=get_post("eventid","i");
			$stime=strtotime(post("stime"));
			$etime=strtotime(post("etime"));
			$data=M("mod_dzp_event")->postData();
			$data["stime"]=$stime;
			$data["etime"]=$etime;
			if($eventid){
				M("mod_dzp_event")->update($data,"eventid='$eventid'");
			}else{
				$data["dateline"]=time();
				M("mod_dzp_event")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$eventid=get_post('eventid',"i");
			$status=get_post("status","i");
			M("mod_dzp_event")->update(array("status"=>$status),"eventid=$eventid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$eventid=get_post('eventid',"i");
			M("mod_dzp_event")->update(array("status"=>11),"eventid=$eventid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>