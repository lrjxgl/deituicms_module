<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsw_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fsw_join&a=default";
			$uid=get("userid","i");
			if($uid){
				$where.=" AND userid=".$uid;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" joinid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fsw_join")->select($option,$rscount);
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
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("fsw_join/index.html");
		}
		
		public function onAdd(){
			$joinid=get("joinid","i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$join=M("mod_fsw_join")->selectRow("joinid=".$joinid);
			if(!empty($join["imgsdata"])){
				$arr=explode(",",$join["imgsdata"]);
				foreach($arr as $v){
					$imgsData[]=array(
						"imgurl"=>$v,
						"trueimgurl"=>images_site($v)
					);
				}
			}
			$this->smarty->goAssign(array(
				"data"=>$join,
				"imgsdata"=>$imgsData
			));
			$this->smarty->display("fsw_join/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=post("actid","i");
			$act=M("mod_fsw_activity")->selectRow("actid=".$actid);
			$nickname=post("nickname","h");
			$telephone=post("telephone","h");
			
			$row=M("mod_fsw_join")->selectRow("userid=".$userid." AND actid=".$actid);
			if($row){
				$this->goAll("已经报名了",1);
			}
			M("mod_fsw_join")->begin();
			M("mod_fsw_join")->insert(array(
				"userid"=>$userid,
				"actid"=>$actid,
				"mid"=>$act["mid"],
				"nickname"=>$nickname,
				"telephone"=>$telephone,
				"createtime"=>date("Y-m-d H:i:s")
			));
			//更新地址
			M("user_lastaddr")->add(array(
				"nickname"=>$nickname,
				"telephone"=>$telephone
			),$userid);
			//查询全部
			$r1=M("mod_fsw_user")->selectRow("userid=".$userid);
			if(!$r1){
				M("mod_fsw_user")->insert(array(
					"userid"=>$userid
				));
			}else{
				M("mod_fsw_user")->changenum("join_num",1,"userid=".$userid);
			}
			//查询联赛
			$r1=M("mod_fsw_match_user")->selectRow("userid=".$userid." AND mid=".$act["mid"]);
			if(!$r1){
				M("mod_fsw_match_user")->insert(array(
					"userid"=>$userid,
					"mid"=>$act["mid"]
				));
			}else{
				M("mod_fsw_match_user")->changenum("join_num",1,"userid=".$userid." AND mid=".$act["mid"]);
			}
			
			M("mod_fsw_join")->commit();
			$this->goAll("参加成功");
		}
		
		public function onchangeweight(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$joinid=post("joinid","i");
			$join=M("mod_fsw_join")->selectRow("joinid=".$joinid);
			$act=M("mod_fsw_activity")->selectRow("actid=".$join["actid"]);
			if($join["userid"]!=$userid && $act["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($join["isfinish"]){
				$this->goAll("已经结束，无法修改");
			}
			$weight=post("weight","f");
			$imgsdata=post("imgsdata","h");
			$description=post("description","h");
			M("mod_fsw_join")->update(array(
				"joinid"=>$joinid,
				"weight"=>$weight,
				"imgsdata"=>$imgsdata,
				"description"=>$description
			),"joinid=".$joinid);
			$this->goAll("success");
		}
		
		public function onCheck(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$joinid=get_post("joinid","i");
			$join=MM("fsw","fsw_join")->selectRow("joinid=".$joinid);
			 
			if(!MM("fsw","fsw_activity")->checkAccess($join["actid"],$userid)){
				$this->goAll("暂无权限",1);
			}
			MM("fsw","fsw_join")->update(array(
				"ischeck"=>1
			),"joinid=".$joinid);
			$this->goAll("签到成功");
		}
		
	}

?>