<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fishing_free_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_free_order")->select($option,$rscount);
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
			$this->smarty->display("fishing_free_order/index.html");
		}
		
		public function onAdd(){
			$orderid=get_post("orderid","i");
			if($orderid){
				$data=M("mod_fishing_free_order")->selectRow(array("where"=>"orderid=".$orderid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_free_order/add.html");
		}
		
		public function onSave(){
			$orderid=get_post("orderid","i");
			$data=M("mod_fishing_free_order")->postData();
			if($orderid){
				M("mod_fishing_free_order")->update($data,"orderid=".$orderid);
			}else{
				M("mod_fishing_free_order")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$row=M("mod_fishing_free_order")->selectRow("orderid=".$orderid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fishing_free_order")->update(array("status"=>$status),"orderid=".$orderid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_fishing_free_order")->update(array("status"=>11),"orderid=".$orderid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>