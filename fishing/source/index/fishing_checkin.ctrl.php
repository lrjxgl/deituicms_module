<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_checkinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_checkin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_checkin")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_checkin/index.html");
		}
		
		public function onList(){
			$placeid=get("placeid","i");
			
			$where=" placeid=".$placeid." AND status in(0,1)";
			$url="/module.php?m=fishing_checkin&a=list";
			$limit=6;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_checkin")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("fishing_checkin/index.html");
		}
		public function onMy(){
			M("login")->userid;
			$userid=M("login")->userid;
			
			$where=" userid=".$userid." AND status in(0,1)";
			$url="/module.php?m=fishing_checkin&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_checkin")->Dselect($option,$rscount);
			 
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
			$this->smarty->display("fishing_checkin/my.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			$placeid=get("placeid","i");
			$place=M("mod_fishing_place")->where("placeid=?")->row($placeid);
			if(empty($place) || $place["status"]!=1){
				$this->goAll("钓点已下线",1);
			}
			 
			$this->smarty->goassign(array(
				 
				"place"=>$place
			));
			$this->smarty->display("fishing_checkin/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_fishing_checkin")->postData();
			$place=M("mod_fishing_place")->selectRow("placeid=".$data["placeid"]);
			$placeid=$place["placeid"];
			if(!$place || $place["status"]>1){
				$this->goAll("钓点已下线",1);
			}
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			$imgsdata=post("imgsdata","h");
			if(!empty($imgsdata)){
				$imgsdata=safeImgsData($imgsdata);
				$data["imgsdata"]=$imgsdata;
				$ex=explode(",",$imgsdata);
				 
			}
			$day=substr($data["createtime"],0,10);
			$row=M("mod_fishing_checkin")->selectRow(array(
				"where"=>" userid=".$userid." AND createtime like '".$day."%' ",
				"order"=>"id DESC"
			));
			if($row){
				$this->goAll("你已经打卡了",1);
			}
			M("mod_fishing_checkin")->insert($data);
			$config=M("mod_fishing_config")->selectRow("1");
			M("mod_fishing_place")->update(array(
				"grade"=>$place["grade"]+$config["checkin_post_grade"],
			),"placeid=".$placeid);
			$this->goall("保存成功");
		}
		
	 
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$row=M("mod_fishing_checkin")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_fishing_checkin")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>