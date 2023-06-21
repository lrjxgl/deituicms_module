<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_activityControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_free_activity&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_activity")->Dselect($option,$rscount);
			
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
			$this->smarty->display("fishing_free_activity/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fishing_free_activity&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_activity")->Dselect($option,$rscount);
			if(!empty($data)){
				$placeids=[];
				foreach($data as $v){
					$placeids[]=$v["placeid"];
				}
				$places=MM("fishing","fishing_free_place")->getListByIds($placeids,"placeid,title,address");
				foreach($data as $k=>$v){
					$v["place"]=$places[$v["placeid"]];
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
			$this->smarty->display("fishing_free_activity/index.html");
		}
		
		public function onShow(){
			$userid=M("login")->userid;
			$actid=get_post("actid","i");
			$data=M("mod_fishing_free_activity")->selectRow(array("where"=>"actid=".$actid));
			$data["imgurl"]=images_site($data["imgurl"]);
			$time=time();
			$sday=strtotime($data["stime"]);
			if($time<$sday){
				//即将开始
				$data["atype"]="baoming";
				$data["atype_title"]="报名中";
			}elseif(date("Y-m-d")==substr($data["sday"],0,10) && !$data["isfinish"]   ){
				$data["atype"]="doing";
				$data["atype_title"]="比赛中";
			}elseif($time>$sday || $data["isfinish"]==1 ){
				$data["atype"]="finish";
				$data["atype_title"]="已结束";
			}
			$place=M("mod_fishing_free_place")->selectRow("placeid=".$data["placeid"]);
			//判断是否已经报名
			$myJoin=MM("fishing","fishing_free_join")->selectRow("actid=".$actid." AND userid=".$userid);
			$isJoin=0;
			if($myJoin){
				$isJoin=1;
			}
			$addr=M("user_lastaddr")->get($userid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"place"=>$place,
				"addr"=>$addr,
				"isJoin"=>$isJoin
			));
			$this->smarty->display("fishing_free_activity/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=fishing_free_activity&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_activity")->Dselect($option,$rscount);
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
			$this->smarty->display("fishing_free_activity/my.html");
		}
		
		
		public function onMyJoin(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" status in(0,1,2) AND userid=".$userid;
			$url="/module.php?m=fishing_free_activity&a=myjoin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fishing","fishing_free_join")->select($option,$rscount);
			if(!empty($data)){
				$actids=[];
				foreach($data as $v){
					$actids[]=$v["actid"];
				}
				$acts=MM("fishing","fishing_free_activity")->getListByIds($actids);
				foreach($data as $k=>$v){
					$v=$acts[$v["actid"]];
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
			$this->smarty->display("fishing_free_activity/myjoin.html");
		}
		
		public function onAdd(){
			$actid=get_post("actid","i");
			$placeid=get_post("placeid","i");
			$iscopy=get("iscopy","i");
			if($actid){
				$data=M("mod_fishing_free_activity")->selectRow(array("where"=>"actid=".$actid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
				$placeid=$data["placeid"];
				if($iscopy){
					 
					$data["title"].="_copy";
				}
			}
			
			$place=M("mod_fishing_free_place")->selectRow("placeid=".$placeid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"place"=>$place,
				"iscopy"=>$iscopy,
				
			));
			$this->smarty->display("fishing_free_activity/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=get_post("actid","i");
			$placeid=post("placeid","i");
			$place=M("mod_fishing_free_place")->selectRow("placeid=".$placeid);
			if($place["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$data=M("mod_fishing_free_activity")->postData();
			if($actid){
				M("mod_fishing_free_activity")->update($data,"actid=".$actid);
			}else{
				$data["userid"]=$userid;
				M("mod_fishing_free_activity")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		
		public function onJoin(){
			$actid=get_post("actid","i");
			$activity=M("mod_fishing_free_activity")->selectRow(array("where"=>"actid=".$actid));
			$list=MM("fishing","fishing_free_join")->Dselect(array(
				"where"=>" actid=".$actid,
				"order"=>" joinid DESC"
			));
			 
			$this->smarty->goAssign(array(
				"activity"=>$activity,
				"list"=>$list,
				"actid"=>$actid
			));
			$this->smarty->display("fishing_free_activity/join.html");
		}
		
	}

?>