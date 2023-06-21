<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fsw_matchControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=fsw_match&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" mid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_match")->Dselect($option,$rscount);
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
			$this->smarty->display("fsw_match/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=fsw_match&a=default";
			$type=get("type","h");
			switch($type){
				case "recommend":
					$where.=" AND isrecommend=1 ";
					$orderby=" grade DESC";
					break;
				case "hot":
					$orderby=" grade DESC";
					break;
				case "near":
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" mid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_match")->Dselect($option,$rscount);
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
			$this->smarty->display("fsw_match/index.html");
		}
		
		public function onShow(){
			$mid=get_post("mid","i");
			$data=M("mod_fsw_match")->selectRow(array("where"=>"mid=".$mid));
			$data["imgurl"]=images_site($data["imgurl"]);
			//比赛列表
			$actList=MM("fsw","fsw_activity")->Dselect(array(
				"where"=>" mid=".$mid,
				"order"=>" actid DESC"
			));
			//积分榜
			$phList=M("mod_fsw_match_user")->select(array(
				"where"=>"mid=".$mid,
				"order"=>" grade DESC",
				"limit"=>100
			));
			if(!empty($phList)){
				$uids=[];
				foreach($phList as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
				foreach($phList as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["user_head"]=$us[$v["userid"]]["user_head"];
					$phList[$k]=$v;
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"actList"=>$actList,
				"phList"=>$phList
			));
			$this->smarty->display("fsw_match/show.html");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2) ";
			$url="/module.php?m=fsw_match&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" mid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("fsw","fsw_match")->Dselect($option,$rscount);
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
			$this->smarty->display("fsw_match/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$mid=get_post("mid","i");
			if($mid){
				$data=M("mod_fsw_match")->selectRow(array("where"=>"mid=".$mid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fsw_match/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$mid=get_post("mid","i");
			$data=M("mod_fsw_match")->postData();
			if($mid){
				M("mod_fsw_match")->update($data,"mid=".$mid);
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_fsw_match")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$mid=get_post('mid',"i");
			$row=M("mod_fsw_match")->selectRow("mid=".$mid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fsw_match")->update(array("status"=>$status),"mid=".$mid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$mid=get_post('mid',"i");
			M("mod_fsw_match")->update(array("status"=>11),"mid=".$mid);
			$this->goAll("删除成功");
			 
		}
		
		public function onMyJoin(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid ;
			$url="/module.php?m=fsw_match&a=myjoin";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"fields"=>" mid ",
				"where"=>$where
			);
			$rscount=true;
			$mids=M("mod_fsw_match_user")->selectCols($option,$rscount);
			$list=[];
			if(!empty($mids)){
				$list=MM("fsw","fsw_match")->getListByIds($mids);
			}
			
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$list,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("fsw_match/myjoin.html");
		}
	}

?>