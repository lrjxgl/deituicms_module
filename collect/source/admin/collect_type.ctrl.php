<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class collect_typeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
 
		}
		
		public function onDefault(){
			$where=" 1=1 ";
			$url="/moduleadmin.php?m=collect_type&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" type_id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_collect_type")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->assign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("collect_type/index.html");
		}
		
		public function onList(){
			$where="";
			$url="/index.php?m=collect_type&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" type_id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_collect_type")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->assign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("collect_type/index.html");
		}
		
		public function onAdd(){
			$type_id=get_post("type_id","i");
			if($type_id){
				$data=M("mod_collect_type")->selectRow(array("where"=>"type_id={$type_id}"));
				
			}
			$this->smarty->assign(array(
				"data"=>$data
			));
			$this->smarty->display("collect_type/add.html");
		}
		
		public function onSave(){
			
			$type_id=get_post("type_id","i");
			$data["title"]=post("title","h");
			$data["orderindex"]=post("orderindex","i");
			$data["status"]=post("status","i");

			if($type_id){
				M("mod_collect_type")->update($data,"type_id='$type_id'");
			}else{
				
				M("mod_collect_type")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$type_id=get_post('type_id',"i");
			$status=get_post("status","i");
			M("mod_collect_type")->update(array("status"=>$status),"type_id=$type_id");
			exit(json_encode(array("error"=>0,"message"=>"状态修改成功")));
		}
		
		public function onDelete(){
			$type_id=get_post('type_id',"i");
			M("mod_collect_type")->delete("type_id={$type_id}");
			exit(json_encode(array("error"=>0,"message"=>"删除成功")));
		}
		
		
	}

?>