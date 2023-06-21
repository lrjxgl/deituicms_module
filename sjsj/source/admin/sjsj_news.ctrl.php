<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sjsj_newsControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			
			$type=get("type","h");
			$url="moduleadmin.php?m=sjsj_news&type=".$type;
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				case "pass":
					$where=" status=1 ";
					break;
				case "success":
					$where.=" AND issuccess=1 ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" newsid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_sjsj_news")->select($option,$rscount);
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
			$this->smarty->display("sjsj_news/index.html");
		}
		
		public function onAdd(){
			$newsid=get_post("newsid","i");
			if($newsid){
				$data=M("mod_sjsj_news")->selectRow(array("where"=>"newsid=".$newsid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("sjsj_news/add.html");
		}
		
		public function onSave(){
			$newsid=get_post("newsid","i");
			$data=M("mod_sjsj_news")->postData();
			if($newsid){
				M("mod_sjsj_news")->update($data,"newsid=".$newsid);
			}else{
				M("mod_sjsj_news")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$newsid=get_post('newsid',"i");
			$row=M("mod_sjsj_news")->selectRow("newsid=".$newsid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_sjsj_news")->update(array("status"=>$status),"newsid=".$newsid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$newsid=get_post('newsid',"i");
			M("mod_sjsj_news")->update(array("status"=>11),"newsid=".$newsid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>