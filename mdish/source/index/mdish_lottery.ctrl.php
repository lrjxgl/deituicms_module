<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mdish_lotteryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$sday=get("sday","i");
			if(!$sday){
				$sday=date("Ymd");
			}
			$where=" status=1 AND sday=".$sday;
			$url="module.php?m=mdish_lottery&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ltid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_lottery")->select($option,$rscount);
			if($data){
				$spids=array();
				foreach($data as  $v){
					$spids[]=$v["shopid"];
				}
				$shops=MM("mdish","mdish_shop")->getListByIds($spids,"shopid,title");
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$v["shop"]=$shops[$v["shopid"]];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
	 
			$userid=M("login")->userid;
			$isJoin=0;
			if($userid){
				$addr=M("user_lastaddr")->get($userid);
				$user=M("user")->selectRow(array(
					"where"=>" userid=".$userid,
					"fields"=>" userid,nickname,money"
				));
				$join=M("mod_mdish_lottery_join")->selectRow("userid=".$userid." AND sday=".$sday);
				if($join){
					$isJoin=1;
				}
			}
			//获取参与用户
			$uids=M("mod_mdish_lottery_join")->selectCols(array(
				"where"=>"sday=".$sday,
				"fields"=>"userid"
			));
			$joinList=array();
			if($uids){
				$joinList=M("user")->getUserByIds($uids);
			}
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"addr"=>$addr,
					"user"=>$user, 
					"rscount"=>$rscount,
					"isJoin"=>$isJoin,
					 "joinList"=>$joinList
				)
			);
			$this->smarty->display("mdish_lottery/index.html");
		}
		
		public function onTomorrow(){
			$sday=date("Ymd",time()+3600*24);
			$where=" status=1 AND sday=".$sday;
			$url="module.php?m=mdish_lottery&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ltid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_lottery")->select($option,$rscount);
			if($data){
				$spids=array();
				foreach($data as  $v){
					$spids[]=$v["shopid"];
				}
				$shops=MM("mdish","mdish_shop")->getListByIds($spids,"shopid,title");
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$v["shop"]=$shops[$v["shopid"]];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"rscount"=>$rscount,
				)
			);
			$this->smarty->display("mdish_lottery/tomorrow.html");
		}
		
		public function onHistory(){
			$sday=date("Ymd",time()-3600*24);
			$where=" status=1 AND sday=".$sday;
			$url="module.php?m=mdish_lottery&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ltid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_lottery")->select($option,$rscount);
			if($data){
				$spids=array();
				foreach($data as  $v){
					$spids[]=$v["shopid"];
				}
				$shops=MM("mdish","mdish_shop")->getListByIds($spids,"shopid,title");
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$v["shop"]=$shops[$v["shopid"]];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"rscount"=>$rscount,
				)
			);
			$this->smarty->display("mdish_lottery/history.html");
		}
		
		public function onShow(){
			$ltid=get_post("ltid","i");
			$data=M("mod_mdish_lottery")->selectRow(array("where"=>"ltid=".$ltid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("mdish_lottery/show.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="module.php?m=mdish_lottery&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" ltid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_mdish_lottery")->select($option,$rscount);
			foreach($data as $k=>$v){
				$v["status_name"]=MM("mdish","mdish_lottery")->getStatus($v["status"]);
				$data[$k]=$v;
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
			$this->smarty->display("mdish_lottery/my.html");
		}
		
		public function onAdd(){
			$userid=M("login")->userid;
			$ltid=get_post("ltid","i");
			if($ltid){
				$data=M("mod_mdish_lottery")->selectRow(array("where"=>"ltid=".$ltid));
				
			}
			$shoplist=M("mod_mdish_shop")->select(array(
				"where"=>" userid=".$userid." AND status=1 ",
				"fields"=>"shopid,title"
			));
			if(empty($shoplist)){
				$this->goAll("请先添加商家",1);
			}
			$sday=date("Ymd",time()+3600*24);
			$this->smarty->goassign(array(
				"data"=>$data,
				"shoplist"=>$shoplist,
				"sday"=>$sday
			));
			$this->smarty->display("mdish_lottery/add.html");
		}
		
		public function onSave(){
			$ltid=get_post("ltid","i");
			$un=array("view_num","love_num","status");
			$data=M("mod_mdish_lottery")->postData($un);
			$data["userid"]=M("login")->userid;
			$data["dateline"]=time();
			$data["sday"]=date("Ymd",time()+3600*24);
			M("mod_mdish_lottery")->insert($data);
			
			$this->goall("保存成功");
		}
		
		public function onCopy(){
			$ltid=get_post("ltid","i");
			$userid=M("login")->userid;
			$data=M("mod_mdish_lottery")->selectRow("ltid=".$ltid);
			if($data["userid"]!=$userid){
				$this->goAll("无权复制",1);
			}
			$data["sday"]=date("Ymd",time()+3600*24);
			$data["dateline"]=time();
			unset($data["ltid"]);
			$data["love_num"]=0;
			M("mod_mdish_lottery")->insert($data);
			
			$this->goall("复制成功");
		} 
		
		public function onDelete(){
			$ltid=get_post('ltid',"i");
			M("mod_mdish_lottery")->update(array("status"=>11),"ltid=$ltid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>