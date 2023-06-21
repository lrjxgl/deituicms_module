<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class f2c_team_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
		
			
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			$url="/moduleadmin.php?m=f2c_team_apply&type=".$type;
			switch($type){
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
					break;
				default:
						$where=" status=0 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" teamid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_f2c_team_apply")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					 
					$v["usercard"]=images_site($v["usercard"]);
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
			$this->smarty->display("f2c_team_apply/index.html");
		}
		
		public function onPass(){
			$teamid=get("teamid","i");
			$row=M("mod_f2c_team_apply")->selectRow("teamid=".$teamid);
			if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			$user=M("user")->selectRow("userid=".$row["userid"]);
			$_POST=$row;
			$indata=M("mod_f2c_team")->postData();
			$indata["status"]=0;
			unset($indata["teamid"]);
			unset($indata["scid"]);
			$indata["userhead"]=images_site($user["user_head"]);
			M("mod_f2c_team")->insert($indata);
			M("mod_f2c_team_apply")->update(array(
				"status"=>1
			),"teamid=".$teamid);
			//插入团
			$this->goAll("审核成功");
		}
		
		public function onForbid(){
			$teamid=get("teamid","i");
			$row=M("mod_f2c_team_apply")->selectRow("teamid=".$teamid);
				if($row["status"]!=0){
				$this->goAl("已经处理过了",1);
			}
			M("mod_f2c_team_apply")->update(array(
				"status"=>2
			),"teamid=".$teamid);
			$this->goAll("禁止成功");
		}
		
		public function onDelete(){
			$teamid=get_post('teamid',"i");
			M("mod_f2c_team_apply")->update(array("status"=>11),"teamid=$teamid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>