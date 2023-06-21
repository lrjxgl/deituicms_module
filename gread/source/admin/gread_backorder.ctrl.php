<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gread_backorderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="";
			$url="/module.php?m=gread_backorder&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>intval(get_post('per_page')),
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gread_backorder")->select($option,$rscount);
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("gread_backorder/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			if($orderid){
				$data=M("mod_gread_backorder")->selectRow(array("where"=>"orderid={$orderid}"));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("gread_backorder/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$bstatus=get_post("bstatus","i");
			M("mod_gread_backorder")->update(array("bstatus"=>$bstatus),"orderid=$orderid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_gread_backorder")->update(array("bstatus"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>