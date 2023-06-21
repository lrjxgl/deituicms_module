<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_chat_tagControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onDefault(){
			
		}	
		public function onMyTab(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1)";
			$url="/module.php?m=aichat_chat_tag&a=mytab";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_chat_tag")->select($option,$rscount);
			if(empty($data)){
				M("mod_aichat_chat_tag")->insert(array(
					"userid"=>$userid,
					"title"=>"默认",
					"createtime"=>date("Y-m-d H:i:s")
				));
			}
			$data=M("mod_aichat_chat_tag")->select($option,$rscount);
			$tagids=[];
			foreach($data as $v){
				$tagids[]=$v["tagid"];
			}
			$res=M("mod_aichat_chat_msg")->select(array(
				"where"=>" tagid in("._implode($tagids).") "
			));
			$mss=[];
			if(!empty($res)){
				foreach($res as $rs){
					$ms=str2arr($rs["content"]);
					$mss[$rs["tagid"]]=$ms;
				}
			}
			foreach($data as $k=>$v){
				$v["msgList"]=isset($mss[$v["tagid"]])?$mss[$v["tagid"]]:[];
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
			$this->smarty->display("aichat_chat_tag/index.html");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=aichat_chat_tag&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" tagid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_chat_tag")->select($option,$rscount);
			if(empty($data)){
				M("mod_aichat_chat_tag")->insert(array(
					"userid"=>$userid,
					"title"=>"默认",
					"createtime"=>date("Y-m-d H:i:s")
				));
			}
			$data=M("mod_aichat_chat_tag")->select($option,$rscount);
			$tagids=[];
			foreach($data as $v){
				$tagids[]=$v["tagid"];
			}
			$res=M("mod_aichat_chat_msg")->select(array(
				"where"=>" tagid in("._implode($tagids).") "
			));
			$mss=[];
			if(!empty($res)){
				foreach($res as $rs){
					$mss[$rs["tagid"]]=$rs;
				}
			}
			foreach($data as $k=>$v){
				$v["msgList"]=isset($mss[$v["tagid"]])?$mss[$v["tagid"]]:[];
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
			$this->smarty->display("aichat_chat_tag/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$tagid=get_post("tagid","i");
			if($tagid){
				$data=M("mod_aichat_chat_tag")->selectRow(array("where"=>"tagid=".$tagid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_chat_tag/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$tagid=get_post("tagid","i");
			$data=M("mod_aichat_chat_tag")->postData();
			if($tagid){
				$row=M("mod_aichat_chat_tag")->selectRow("tagid=".$tagid);
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_aichat_chat_tag")->update($data,"tagid=".$tagid);
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_aichat_chat_tag")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$tagid=get_post('tagid',"i");
			$row=M("mod_aichat_chat_tag")->selectRow("tagid=".$tagid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_chat_tag")->update(array("status"=>$status),"tagid=".$tagid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$tagid=get_post('tagid',"i");
			$row=M("mod_aichat_chat_tag")->selectRow("tagid=".$tagid);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_aichat_chat_tag")->update(array("status"=>11),"tagid=".$tagid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>