<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class aichat_promptControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=1 ";
			$url="/module.php?m=aichat_prompt&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_prompt")->select($option,$rscount);
			$userid=M("login")->userid;
			if(!empty($data)){
				$favIds=M("fav")->selectCols(array(
					"where"=>" userid=".$userid." AND tablename='mod_aichat_prompt'  ",
					"fields"=>"objectid"
				));
				foreach($data as $k=>$v){
					if(in_array($v["id"],$favIds)){
						$v["isfav"]=1;
					}else{
						$v["isfav"]=0;
					}
					
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
			$this->smarty->display("aichat_prompt/index.html");
		}
		
		public function onList(){
			$where=" status=1";
			$url="/module.php?m=aichat_prompt&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_prompt")->select($option,$rscount);
			$userid=M("login")->userid;
			if(!empty($data)){
				$favIds=M("fav")->selectCols(array(
					"where"=>" userid=".$userid." AND tablename='mod_aichat_prompt'  ",
					"fields"=>"objectid"
				));
				foreach($data as $k=>$v){
					if(in_array($v["id"],$favIds)){
						$v["isfav"]=1;
					}else{
						$v["isfav"]=0;
					}
					
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
			$this->smarty->display("aichat_prompt/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_aichat_prompt")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_prompt/show.html");
		}
		
		
		public function onMy(){
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2) ";
			$url="/module.php?m=aichat_prompt&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_prompt")->select($option,$rscount);
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
			$this->smarty->display("aichat_prompt/my.html");
		}
		
		public function onAdd(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			if($id){
				$data=M("mod_aichat_prompt")->selectRow(array("where"=>"id=".$id));
				if($data["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("aichat_prompt/add.html");
		}
		
		public function onSave(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_aichat_prompt")->postData();
			$data["updatetime"]=date("Y-m-d H:i:s");
			if($id){
				$row=M("mod_aichat_prompt")->selectRow(array("where"=>"id=".$id));
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_aichat_prompt")->update($data,"id=".$id);
			}else{
				$data["userid"]=$userid;
				$data["createtime"]=date("Y-m-d H:i:s");
				M("mod_aichat_prompt")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_aichat_prompt")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_aichat_prompt")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$id=get_post('id',"i");
			$row=M("mod_aichat_prompt")->selectRow("id=".$id);
			if($row["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_aichat_prompt")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
		
		public function onFav(){
			$userid=M("login")->userid;
			$favIds=M("fav")->selectCols(array(
				"where"=>" userid=".$userid." AND tablename='mod_aichat_prompt'  ",
				"fields"=>"objectid",
				"order"=>" id DESC",
				"limit"=>1000
			));
			$where=" id in("._implode($favIds).") ";
			$url="/module.php?m=aichat_prompt&a=fav";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_aichat_prompt")->select($option,$rscount);
			 
			if(!empty($data)){
				 
				foreach($data as $k=>$v){
					$v["isfav"]=1;
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
			$this->smarty->display("aichat_prompt/fav.html");
		}
		
	}

?>