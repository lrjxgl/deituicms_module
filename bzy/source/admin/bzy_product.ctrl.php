<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bzy_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$eventid=get("eventid","i");
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=bzy_product&a=default";
			if($eventid){
				$where.=" AND eventid=".$eventid;
				$url.="&eventid=".$eventid;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bzy_product")->select($option,$rscount);
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
					"eventid"=>$eventid,
					"event"=>M("mod_bzy_event")->selectRow(array(
						"where"=>" eventid=".$eventid,
						"fields"=>"eventid,title"
					))
				)
			);
			$this->smarty->display("bzy_product/index.html");
		}
		
		public function onAdd(){
			$productid=get_post("productid","i");
			$eventid=get("eventid","i");
			if($productid){
				$data=M("mod_bzy_product")->selectRow(array("where"=>"productid=".$productid));
				$eventid=$data["eventid"];
			}
			$ptypeList=MM("bzy","bzy_product")->ptypeList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"eventid"=>$eventid,
				"event"=>M("mod_bzy_event")->selectRow(array(
					"where"=>" eventid=".$eventid,
					"fields"=>"eventid,title"
				)),
				"ptypeList"=>$ptypeList
			));
			$this->smarty->display("bzy_product/add.html");
		}
		
		public function onSave(){
			$productid=get_post("productid","i");
			$data=M("mod_bzy_product")->postData();
			if($productid){
				M("mod_bzy_product")->update($data,"productid='$productid'");
			}else{
				M("mod_bzy_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$productid=get_post('productid',"i");
			$status=get_post("status","i");
			M("mod_bzy_product")->update(array("status"=>$status),"productid=$productid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_bzy_product")->update(array("status"=>11),"productid=$productid");
			$this->goAll("删除成功");
			 
		}
		
		public function onGailv(){
			$productid=get_post('productid',"i");
			$gailv=get_post("gailv","h");
			M("mod_bzy_product")->update(array("gailv"=>$gailv),"productid=$productid");
			$this->goall("修改成功");
		}
	}

?>