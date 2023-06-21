<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zupu_chat_msgControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zupu_chat_msg&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zupu_chat_msg")->select($option,$rscount);
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
			$this->smarty->display("zupu_chat_msg/index.html");
		}
		
		public function onHome(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zupu_chat_msg&a=default";
			$limit=48;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zupu","zupu_chat_msg")->Dselect($option,$rscount);
			if(!empty($data)){
				$ids=[];
				foreach($data as $v){
					$ids[]=$v["id"];
				}
				array_multisort($data,$ids,SORT_ASC);
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
			$this->smarty->display("zupu_chat_msg/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_zupu_chat_msg")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zupu_chat_msg/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_zupu_chat_msg")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zupu_chat_msg/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$id=get_post("id","i");
			$data=M("mod_zupu_chat_msg")->postData();
			$data["userid"]=M("login")->userid;
			if($id){
				M("mod_zupu_chat_msg")->update($data,"id='$id'");
			}else{
				M("mod_zupu_chat_msg")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_zupu_chat_msg")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_zupu_chat_msg")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>