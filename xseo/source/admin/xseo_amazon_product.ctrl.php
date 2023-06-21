<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class xseo_amazon_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=xseo_amazon_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_xseo_amazon_product")->select($option,$rscount);
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
			$this->smarty->display("xseo_amazon_product/index.html");
		}
		
		public function onAdd(){
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_xseo_amazon_product")->selectRow(array("where"=>"productid=".$productid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("xseo_amazon_product/add.html");
		}
		
		public function onSave(){
			$productid=get_post("productid","i");
			$data=M("mod_xseo_amazon_product")->postData();
			if($productid){
				M("mod_xseo_amazon_product")->update($data,"productid=".$productid);
			}else{
				M("mod_xseo_amazon_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$productid=get_post('productid',"i");
			$row=M("mod_xseo_amazon_product")->selectRow("productid=".$productid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_xseo_amazon_product")->update(array("status"=>$status),"productid=".$productid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_xseo_amazon_product")->update(array("status"=>11),"productid=".$productid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>