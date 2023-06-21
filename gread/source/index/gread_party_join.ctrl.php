<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_party_joinControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$pid=get("pid","i");
			$where=" pid=".$pid." AND status in(0,1,2)";
			$url="/module.php?m=gread_party_join&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party_join")->select($option,$rscount);
			if($data){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
			$this->smarty->display("gread_party_join/index.html");
		}
		 
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			
			$pid=get_post("pid","i");
			$party=M("mod_party")->selectRow(array(
				"where"=>"id=".$pid,
				"fields"=>"*"
			));
			if($party["status"]!=1){
				$this->goAll("活动未上线",1);
			}
			if($party["max_num"]<=$party["join_num"]){
				$this->goAll("活动参与人数已满",1);
			}
			$row=M("mod_party_join")->selectRow("userid=".$userid." AND pid=".$pid);
			if($row){
				$this->goAll("你已经参加了",1);
			}
			$data=M("mod_party_join")->postData();
			$data["userid"]=$userid;
			$data["createtime"]=date("Y-m-d H:i:s");
			$data["shopid"]=$party["shopid"];
			$data["money"]=$party["money"];
			M("mod_party_join")->insert($data);
			M("mod_party")->update(array(
				"join_num"=>$party["join_num"]+1
			),"id=".$pid);
			$this->goall("保存成功");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=gread_party_join&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party_join")->select($option,$rscount);
			
			if($data){
				$ids=[];
				foreach($data as $v){
					$ids[]=$v["pid"];
				}
				$ps=MM("gread","gread_party")->getListByIds($ids);
				foreach($data as $k=>$v){
					
					$data[$k]=$ps[$v["pid"]];
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
			$this->smarty->display("gread_party_join/my.html");
		}
		
		 
		
		public function onDelete(){
			$pid=get_post('pid',"i");
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$row=M("mod_party_join")->selectRow(" userid=".$userid." AND pid=".$pid);
			if(empty($row) || $row["status"]>1){
				$this->goAll("删除失败",1);
			}
			M("mod_party_join")->update(array("status"=>11)," userid=".$userid." AND pid=".$pid);
			M("mod_party")->changenum("join_num",-1,"id=".$pid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>