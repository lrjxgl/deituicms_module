<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jieti_ask_answerControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=jieti_ask_answer&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>"  DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jieti_ask_answer")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("jieti_ask_answer/index.html");
		}
		
		public function onAdd(){
			$=get_post("","i");
			if($){
				$data=M("mod_jieti_ask_answer")->selectRow(array("where"=>"={$}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("jieti_ask_answer/add.html");
		}
		
		public function onSave(){
			$=get_post("","i");
			$data=M("mod_jieti_ask_answer")->postData();
			if($){
				M("mod_jieti_ask_answer")->update($data,"='$'");
			}else{
				M("mod_jieti_ask_answer")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$=get_post('',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_jieti_ask_answer")->update(array("bstatus"=>$bstatus),"=$");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$=get_post('',"i");
			M("mod_jieti_ask_answer")->update(array("bstatus"=>11),"=$");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>