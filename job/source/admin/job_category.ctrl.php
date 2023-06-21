<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class job_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$tablename=get_post("tablename","h");
			$where=" tablename='".$tablename."' AND status in(0,1,2)";
			$url="/moduleadmin.php?m=job_category&tablename=".$tablename;
			$limit=200000;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$rss=M("mod_job_category")->select($option,$rscount);
			if($rss){
				foreach($rss as $rs){
					if($rs["pid"]==0){
						$catlist[]=$rs;
					}else{
						$child[$rs["pid"]][]=$rs;
					}
				}
				foreach($catlist as $k=>$v){
					$v["child"]=$child[$v["catid"]];
					$catlist[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"catlist"=>$catlist,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"tablename"=>$tablename
				)
			);
			$this->smarty->display("job_category/index.html");
		}
		
		public function onAdd(){
			$tablename=get_post("tablename","h");
			$catid=get_post("catid","i");
			if($catid){
				$data=M("mod_job_category")->selectRow(array("where"=>"catid={$catid}"));	
			}
			$catlist=M("mod_job_category")->select(array(
				"where"=>" status=1 AND pid=0 AND tablename='".$tablename."' "
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist,
				"tablename"=>$tablename
			));
			$this->smarty->display("job_category/add.html");
		}
		
		public function onSave(){
			$tablename=get_post("tablename","h");
			$catid=get_post("catid","i");
			$data=M("mod_job_category")->postData();
			if($catid){
				M("mod_job_category")->update($data,"catid='$catid'");
			}else{
				M("mod_job_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$catid=get_post('catid',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_job_category")->update(array("bstatus"=>$bstatus),"catid=$catid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			M("mod_job_category")->update(array("bstatus"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>