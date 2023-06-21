<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_partyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" tablename='gread' AND status in(0,1) ";
			$url="/module.php?m=gread_party&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party")->select($option,$rscount);
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
			$this->smarty->display("gread_party/index.html");
		}
		
		public function onList(){
			$where=" tablename='gread' AND status in(0,1)";
			$url="/module.php?m=gread_party&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party")->select($option,$rscount);
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
			$this->smarty->display("gread_party/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_party")->selectRow(array("where"=>"id=".$id));
			$author=M("user")->getUser($data["userid"]);
			$data["nickname"]=$author["nickname"];
			$data["user_head"]=$author["user_head"];
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_party/show.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where="   tablename='gread' AND userid=".$userid." AND status in(0,1,2)  ";
			$url="/module.php?m=gread_party&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_party")->select($option,$rscount);
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
			$this->smarty->display("gread_party/my.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			if($id){
				$data=M("mod_party")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_party/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_party")->postData();
			if(empty($data["title"])){
				$this->goAll("请输入活动名称",1);
			}
			if($data["max_num"]<1){
				$this->goAll("请输入活动限制人数",1);
			}
			
			$shopid=MM("gread","gread")->getSHopid();
			$data["tablename"]="gread";
			$data["objectid"]=$shopid; 
			if($id){
				M("mod_party")->update($data,"id='$id'");
			}else{
				$data["userid"]=$userid;
				$data["shopid"]=$shopid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_party")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_party")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$status=get_post("status","i");
			M("mod_party")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_party")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_party")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>