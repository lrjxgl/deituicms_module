<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) ";
			$url="/module.php?m=fishing_free_join&a=default";
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
			$data=M("mod_fishing_free_join")->select($option,$rscount);
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
			$this->smarty->display("fishing_free_join/index.html");
		}
		
		public function onList(){
			$actid=get("actid","i");
			$where=" actid=".$actid." AND status in(0,1) ";
			$url="/module.php?m=fishing_free_join&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" joinid DESC",
				"fields"=>"userid,nickname,userid",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_free_join")->select($option,$rscount);
			if(!empty($data)){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["user_head"]=$us[$v["userid"]]["user_head"];
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
			$this->smarty->display("fishing_free_join/index.html");
		}
		
		public function onAdd(){
			$joinid=get_post("joinid","i");
			if($joinid){
				$data=M("mod_fishing_free_join")->selectRow(array("where"=>"joinid=".$joinid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_free_join/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$actid=post("actid","i");
			$nickname=post("nickname","h");
			$telephone=post("telephone","h"); 
			$row=MM("fishing","fishing_free_join")->selectRow("userid=".$userid." AND actid=".$actid);
			if(!empty($row)){
				$this->goAll("你已经申请参加了",1);
			}
			MM("fishing","fishing_free_join")->begin();
			MM("fishing","fishing_free_join")->insert(array(
				"actid"=>$actid,
				"userid"=>$userid,
				"nickname"=>$nickname,
				"telephone"=>$telephone,
				"createtime"=>date("Y-m-d H:i:s")
			));
			//更新地址
			M("user_lastaddr")->add(array(
				"nickname"=>$nickname,
				"telephone"=>$telephone
			),$userid);
			MM("fishing","fishing_free_join")->commit();
			$this->goall("保存成功");
		}
		public function onCheck(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$joinid=get("joinid","i");
			MM("fishing","fishing_free_join")->update(array(
				"ischeck"=>1
			),"joinid=".$joinid);
			$this->goAll("签到成功");
		}
		
	}

?>