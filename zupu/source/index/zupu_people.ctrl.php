<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zupu_peopleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zupu_people&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zupu_people")->select($option,$rscount);
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
			$this->smarty->display("zupu_people/index.html");
		}
		
		
		public function onHome(){
			$gid=1;
			$pid=get("pid","i");
			$nickname=get("nickname","h");
			if($nickname){
				$me=MM("zupu","zupu_people")->selectRow("nickname='".$nickname."'");
				if(empty($me)){
					$pid=0;
				}else{
					$pid=$me["id"];
				}
				
			}else{
				$me=MM("zupu","zupu_people")->selectRow("id=".$pid);
			}
			
			$group=M("mod_zupu_group")->selectRow("gid=".$gid);
			
			$parent=[];
			$us=[];
			if($me){
				$parent=MM("zupu","zupu_people")->selectRow("id=".$me["pid"]);
				 
				$us=MM("zupu","zupu_people")->select(array(
					"where"=>"pid=".$me["pid"]
				));
			}
			
			$child=MM("zupu","zupu_people")->select(array(
				"where"=>"pid=".$pid
			));
			$list=MM("zupu","zupu_people")->children(1,0);
			$this->smarty->goAssign(array(
				"me"=>$me,
				"parent"=>$parent,
				"us"=>$us,
				"child"=>$child,
				"group"=>$group
			));
			$this->smarty->display("zupu_people/home.html");
		}
		
		public function onList(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=zupu_people&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_zupu_people")->select($option,$rscount);
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
			$this->smarty->display("zupu_people/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_zupu_people")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zupu_people/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_zupu_people")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zupu_people/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_zupu_people")->postData();
			if($id){
				M("mod_zupu_people")->update($data,"id='$id'");
			}else{
				M("mod_zupu_people")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_zupu_people")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_zupu_people")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>