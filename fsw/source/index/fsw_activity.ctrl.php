<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsw_activityControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fsw_activity&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsw_activity")->select($option,$rscount);
			if(!empty($data)){
				foreach($data as &$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("fsw_activity/index.html");
		}
		
		public function onList(){
			$where=" status in(0,1) ";
			$url="/module.php?m=fsw_activity&a=list";
			$type=get("type","h");
			$sday=date("Y-m-d");
			switch($type){
				case "baoming":
					
					$where=" status in(0,1) AND sday>'".$sday."' ";
					break;
				case "doing":
					$where=" status in(0,1) AND sday='".$sday."' ";
					break;
				case "finish":
					$where=" status in(0,1) AND sday<'".$sday."' ";
					break;
			}
		 
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_activity")->Dselect($option,$rscount);
			if(!empty($data)){
				foreach($data as &$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("fsw_activity/index.html");
		}
		
		public function onShow(){
			$actid=get_post("actid","i");
			$data=M("mod_fsw_activity")->selectRow(array("where"=>"actid=".$actid));
			$data["imgurl"]=images_site($data["imgurl"]);
			$joinList=MM("fsw","fsw_join")->Dselect(array(
				"where"=>" actid=".$actid,
				"order"=>" weight DESC"
			));
			$time=time();
			$sday=strtotime($data["sday"]);
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
			$userid=M("login")->userid;
			$fswUser=MM("fsw","fsw_user")->get($userid);
			unset($fswUser["telephone"]);
			$this->smarty->goassign(array(
				"data"=>$data,
				"joinList"=>$joinList,
				"fswUser"=>$fswUser
			));
			$this->smarty->display("fsw_activity/show.html");
		}
		public function onJoin(){
			$actid=get_post("actid","i");
			$activity=M("mod_fsw_activity")->selectRow(array("where"=>"actid=".$actid));
			$list=MM("fsw","fsw_join")->Dselect(array(
				"where"=>" actid=".$actid,
				"order"=>" weight DESC"
			));
			$this->smarty->goAssign(array(
				"activity"=>$activity,
				"list"=>$list
			));
			$this->smarty->display("fsw_activity/join.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=fsw_activity&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" actid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_activity")->Dselect($option,$rscount);
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
			$this->smarty->display("fsw_activity/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=get_post("actid","i");
			$mid=get("mid","i");
			
			if($actid){
				$data=M("mod_fsw_activity")->selectRow(array("where"=>"actid=".$actid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
				$mid=$data["mid"];
			}
			$match=M("mod_fsw_match")->selectRow("mid=".$mid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"match"=>$match
			));
			$this->smarty->display("fsw_activity/add.html");
		}
		
		
		public function onCopy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=get_post("actid","i");
			$data=M("mod_fsw_activity")->selectRow(array("where"=>"actid=".$actid));
			$data["trueimgurl"]=images_site($data["imgurl"]);
			$mid=$data["mid"];
			$match=M("mod_fsw_match")->selectRow("mid=".$mid);
			$this->smarty->goassign(array(
				"data"=>$data,
				"match"=>$match,
				"iscopy"=>1
			));
			$this->smarty->display("fsw_activity/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=get_post("actid","i");
			$data=M("mod_fsw_activity")->postData();
			 
			if($actid){
				M("mod_fsw_activity")->update($data,"actid=".$actid);
			}else{
				$data["userid"]=$userid;
				 
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_fsw_activity")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$actid=get_post('actid',"i");
			$row=M("mod_fsw_activity")->selectRow("actid=".$actid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fsw_activity")->update(array("status"=>$status),"actid=".$actid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$actid=get_post('actid',"i");
			M("mod_fsw_activity")->update(array("status"=>11),"actid=".$actid);
			$this->goAll("删除成功");
			 
		}
		
		public function onFinish(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=post("actid","i");
			$act=M("mod_fsw_activity")->selectRow("actid=".$actid);
			if($act["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_fsw_activity")->begin();
			M("mod_fsw_activity")->update(array(
				"isfinish"=>1
			),"actid=".$actid);
			
			$rscount=M("mod_fsw_join")->getCount("actid=".$actid);
			if($rscount>=30){
				$limit=6;
				$maxGrade=6;
			}elseif($rscount>=20){
				$limit=5;
				$maxGrade=5;
			}elseif($rscount>=10){
				$limit=4;
				$maxGrade=4;
			}elseif($rscount>=8){
				$limit=3;
				$maxGrade=3;
			}elseif($rscount>=5){
				$limit=2;
				$maxGrade=2;
			}elseif($rscount>=3){
				$limit=1;
				$maxGrade=1;
			}else{
				$limit=0;
			}
			if($limit>0){
				$joinList=M("mod_fsw_join")->select(array(
					"where"=>" actid=".$actid." AND weight>0 ",
					"order"=>" weight DESC",
					"limit"=>$limit
				));
				if(!empty($joinList)){
					foreach($joinList as $v){
						M("mod_fsw_join")->update(array(
							"grade"=>$maxGrade
						),"joinid=".$v["joinid"]);
						M("mod_fsw_user")->changenum("grade",$maxGrade,"userid=".$v["userid"]);
						M("mod_fsw_match_user")->changenum("grade",$maxGrade,"userid=".$v["userid"]." AND mid=".$v["mid"]);
						$maxGrade--;
					}
				}
				
			}
			
			//积分规则
			M("mod_fsw_activity")->commit();
			$this->goAll("比赛结束");
			
		}
		
		public function onMyJoin(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=fsw_activity&a=myjoin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" joinid DESC",
				"fields"=>"*",
				"where"=>$where
			);
			$rscount=true;
			$res=M("mod_fsw_join")->select($option,$rscount);
			$list=[];
			if(!empty($res)){
				$actIds=[];
				foreach($res as $rs){
					$actIds[]=$rs["actid"];
				}
				$acts=MM("fsw","fsw_activity")->getListByIds($actIds);
				foreach($res as $k=>$v){
					$v=$v+$acts[$v["actid"]];
					$res[$k]=$v;
				} 
			} 
				
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$res,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("fsw_activity/myjoin.html");
		}
		
	}

?>