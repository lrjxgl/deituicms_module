<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_loupanControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" 1 ";
			$url="/moduleadmin.php?m=house_loupan&a=list";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_loupan")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("house_loupan/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_house_loupan")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("house_loupan/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");
			$data=M("mod_house_loupan")->postData();

			if($id){
				M("mod_house_loupan")->update($data,"id='$id'");
			}else{
				M("mod_house_loupan")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_house_loupan")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_house_loupan")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_loupan")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_house_loupan")->selectRow("id=".$id);
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_house_loupan")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
		}
		public function onIsBuy(){
			$id=get_post('id',"i");
			$row=M("mod_house_loupan")->selectRow("id=".$id);
			$isbuy=0;
			if($row["isbuy"]==0){
				$isbuy=1;
			}
			M("mod_house_loupan")->update(array(
				"isbuy"=>$isbuy
			),"id=".$id);
			$this->goAll("success",0,$isbuy);
		}
	}

?>