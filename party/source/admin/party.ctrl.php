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
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$type=get("type","h");
			$time=date("Y-m-d H:i:s");
			$url="/moduleadmin.php?m=party&type=".$type;
			switch($type){
				case "new":
						$where=" status=0 ";
					break;
				case "doing":
						$where=" status=1 AND isfinish=0 AND stime<'".$time."' ";
					break;
				case "unbegin":
						$where=" status=1 AND isfinish=0 AND stime>'".$time."' ";
					break;
				case "finish":
					$where=" status=1 AND isfinish=1 ";
					break;
				default:
					$where=" status in(0,1,2)";
					break;
			}
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
			$this->smarty->display("party/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_party")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("party/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_party")->postData();
			if($id){
				M("mod_party")->update($data,"id='$id'");
			}else{
				M("mod_party")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_party")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_party")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onPass(){
			$id=get("id","i");
			
			$party=m("mod_party")->selectRow("id=".$id);
			if(empty($party)){
				$this->goAll("活动不存在",1);
			}
			 
			M("mod_party")->update(array(
				"status"=>1
			),"id=".$id);
			//消息推送
			M("notice")->add(array(
				"userid"=>$party["userid"],
				"content"=>"您发起的".$party["title"]."活动通过审核了",
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"party",
					"a"=>"show",
					"param"=>"id=".$party["id"]
				)
			));
			$this->goAll("success");
			
		}
		
		public function onForbid(){
			$id=get("id","i");
			 
			$party=m("mod_party")->selectRow("id=".$id);
			if(empty($party)){
				$this->goAll("活动不存在",1);
			}
			 
			M("mod_party")->update(array(
				"status"=>2
			),"id=".$id);
			//消息推送
			M("notice")->add(array(
				"userid"=>$party["userid"],
				"content"=>"您发起的".$party["title"]."活动未通过审核",
				"linkurl"=>array(
					"path"=>"/module.php",
					"m"=>"party",
					"a"=>"add",
					"param"=>"id=".$party["id"]
				)
			));
			$this->goAll("success");
			
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_party")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>