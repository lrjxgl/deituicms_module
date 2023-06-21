<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class sharepic_typeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=sharepic_type&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" typeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_sharepic_type")->select($option,$rscount);
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
			$this->smarty->display("sharepic_type/index.html");
		}
		
		public function onAdd(){
			$typeid=get_post("typeid","i");
			if($typeid){
				$data=M("mod_sharepic_type")->selectRow(array("where"=>"typeid=".$typeid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("sharepic_type/add.html");
		}
		
		public function onSave(){
			$typeid=get_post("typeid","i");
			$data=M("mod_sharepic_type")->postData();
			if($typeid){
				M("mod_sharepic_type")->update($data,"typeid='$typeid'");
			}else{
				M("mod_sharepic_type")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$typeid=get_post('typeid',"i");
			$statusget_post("sstatus"i");
			M("mod_sharepic_type")->update(array("status=>$sstatus"typeid=$typeid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$typeid=get_post('typeid',"i");
			M("mod_sharepic_type")->update(array("status=>11),"typeid=$typeid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>