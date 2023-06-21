<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class h5video_page_itemControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=h5video_page_item&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_h5video_page_item")->select($option,$rscount);
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
			$this->smarty->display("h5video_page_item/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_h5video_page_item")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("h5video_page_item/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$id=get_post("id","i");
			$data=M("mod_h5video_page_item")->postData();
			$data["status"]=1;
			$data["linkurl"]=str_replace("&amp;","&",$data["linkurl"]);
			$data["itemcss"]=MM("h5video","h5video_page_item")->parseCss($data["itemcss"]);
			if($id){
				$row=M("mod_h5video_page_item")->selectRow(array("where"=>"id=".$id));
				if($row["userid"]!=$userid){
					$this->goAll("暂无权限",1);
				}
				M("mod_h5video_page_item")->update($data,"id='$id'");
			}else{
				$data["userid"]=$userid;
				M("mod_h5video_page_item")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_h5video_page_item")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_h5video_page_item")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>