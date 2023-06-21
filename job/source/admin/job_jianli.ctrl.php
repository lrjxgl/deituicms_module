<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class job_jianliControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			
			$type=get("type","h");
			$url="/module.php?m=job_jianli&type=".$type;
			switch($type){
				case "new":
					$where=" status=0 ";
					break;
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
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
			$data=M("mod_job_jianli")->select($option,$rscount);
			if(!empty($data)){
				$xueliList=MM("job","job_jianli")->xueliList;
				foreach($data as $k=>$v){
					$v["xueli"]=$xueliList[$v["xueli"]];
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
					"url"=>$url,
					"type"=>$type
				)
			);
			$this->smarty->display("job_jianli/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_job_jianli")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("job_jianli/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_job_jianli")->postData();
			if($id){
				M("mod_job_jianli")->update($data,"id='$id'");
			}else{
				M("mod_job_jianli")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_job_jianli")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_job_jianli")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_job_jianli")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>