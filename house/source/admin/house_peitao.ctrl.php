<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_peitaoControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$lpid=get("lpid","i");
			$loupan=M("mod_house_loupan")->selectRow("id=".$lpid);
			$where=" lpid=".$lpid." AND status in(0,1,2)";
			$url="/moduleadmin.php?m=house_peitao&lpid=".$lpid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_peitao")->select($option,$rscount);
			$typeList=MM("house","house_peitao")->typeList();
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
					"loupan"=>$loupan,
					"typeList"=>$typeList
				)
			);
			$this->smarty->display("house_peitao/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_house_peitao")->selectRow(array("where"=>"id=".$id));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("house_peitao/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_house_peitao")->postData();
			$data["status"]=1;
			if($id){
				M("mod_house_peitao")->update($data,"id='$id'");
			}else{
				M("mod_house_peitao")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_house_peitao")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_peitao")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>