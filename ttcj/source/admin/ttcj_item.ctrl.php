<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ttcj_itemControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=2 ";
			$url="/module.php?m=ttcj_item&a=default";
			$limit=24;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ttcj_item")->select($option,$rscount);
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
			$this->smarty->display("ttcj_item/index.html");
		}
		
		public function onTtcj(){
			$cjid=get("cjid",'i');
			$ttcj=M("mod_ttcj")->selectRow("cjid=".$cjid);
			
			$data=M("mod_ttcj_item")->select(array(
				"where"=>" cjid=".$cjid." AND status=2 ",
				"order"=>" money ASC"
			));
			 
			$this->smarty->goAssign(array(
				"ttcj"=>$ttcj,
				"data"=>$data
			));
			$this->smarty->display("ttcj_item/ttcj.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_ttcj_item")->selectRow(array("where"=>"id={$id}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("ttcj_item/add.html");
		}
		
		public function onSave(){
			
			$id=get_post("id","i");

			$data=M("mod_ttcj_item")->postData();
			$data['status']=2;
			if($id){
				M("mod_ttcj_item")->update($data,"id='$id'");
			}else{
				M("mod_ttcj_item")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_ttcj_item")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_ttcj_item")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>