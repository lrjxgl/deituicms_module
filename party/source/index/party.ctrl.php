<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class partyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$fromapp=get("fromapp");
			switch($fromapp){
				case "uniapp":
					$flashList=M("ad")->listByNo("uniapp-party-index");
					$adList=M("ad")->listByNo("uniapp-party-ad");
					$navList=M("ad")->listByNo("uniapp-party-nav"); 
					break;
				default:
					$flashList=M("ad")->listByNo("wap-party-index");
					$adList=M("ad")->listByNo("wap-party-ad");
					$navList=M("ad")->listByNo("wap-party-nav"); 
					break;
			}
			$where=" status=1 ";
			$url="/module.php?m=party&a=default";
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
					$v["imgurl"]=images_site($v["imgurl"]);
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$seo=M("seo")->get("party","default");
			$this->smarty->goassign(
				array(
					"seo"=>$seo,
					"flashList"=>$flashList,
					"adList"=>$adList,
					"navList"=>$navList,
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("party/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=party&a=default";
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
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("party/list.html");
		}
		
		public function onShow(){
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_party")->selectRow(array("where"=>"id=".$id));
			if(!$data || $data["status"]>1){
				$this->goall("已经关小黑屋了",1);
			}
			$author=M("user")->getUser($data["userid"]);
			$data["nickname"]=$author["nickname"];
			$data["user_head"]=$author["user_head"];
			$join=M("mod_party_join")->selectRow("pid=".$id." AND userid=".$userid);
			$seo=array(
				"title"=>$data["title"],
				"description"=>$data["description"]
			);
			$this->smarty->goassign(array(
				"data"=>$data,
				"join"=>$join,
				"seo"=>$seo
			));
			$this->smarty->display("party/show.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where="  userid=".$userid." AND status in(0,1,2)  ";
			$tablename=get("tablename","h");
			if($tablename){
				$where.=" AND tablename='".$tablename."' ";
			}
			$url="/module.php?m=party&a=my&tablename=".$tablename;
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
			$this->smarty->display("party/my.html");
		}
		public function onAdd(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			if($id){
				$data=M("mod_party")->selectRow(array("where"=>"id=".$id));
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("party/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$un=array("status");
			$data=M("mod_party")->postData($un);
			$data["status"]=0;
			if(empty($data["title"])){
				$this->goAll("请输入活动名称",1);
			}
			if($data["max_num"]<1){
				$this->goAll("请输入活动限制人数",1);
			}
			if(!is_tel($data["telephone"])){
				$this->goAll("手机号码出错啦",1);
			} 
			
			if($id){
				M("mod_party")->update($data,"id='$id'");
			}else{
				$data["userid"]=$userid;
				 
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_party")->insert($data);
			}
			$this->goall("保存成功");
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