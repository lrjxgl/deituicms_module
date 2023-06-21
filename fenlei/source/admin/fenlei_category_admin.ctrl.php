<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fenlei_category_adminControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$catid=get("catid","i");
			$cat=M("mod_fenlei_category")->selectRow(array(
				"where"=>"catid=".$catid,
				"fields"=>"catid,title"
			));
			$where=" status=1 AND catid=".$catid;
			$url="/moduleadmin.php?m=fenlei_category_admin&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei_category_admin")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"cat"=>$cat,
					"url"=>$url
				)
			);
			$this->smarty->display("fenlei_category_admin/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fenlei_category_admin")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fenlei_category_admin/add.html");
		}
		
		public function onSave(){
			$catid=get_post("catid","i");
			$nickname=post("nickname","h");
			$user=M("user")->selectRow("nickname='".$nickname."' ");
			if(empty($user)){
				$this->goAll("用户不存在",1);
			}
			$userid=$user["userid"];
			$row=M("mod_fenlei_category_admin")->selectRow("catid=".$catid." AND userid=".$userid);
			if($row){
				$this->goAll("已经是版主了",1);
			}
			M("mod_fenlei_category_admin")->insert(array(
				"status"=>1,
				"catid"=>$catid,
				"userid"=>$userid,
				"dateline"=>time()
			));
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fenlei_category_admin")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fenlei_category_admin")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>