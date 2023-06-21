<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_paotui_lmshop_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$lmid=get("lmid","i");
			$lmshop=M("mod_csc_paotui_lmshop")->selectRow("lmid=".$lmid);
			 
			$where=" lmid=".$lmid." AND status in(0,1,2)";
			$url="/moduleshop.php?m=csc_paotui_lmshop_product&lmid=".$lmid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_paotui_lmshop_product")->select($option,$rscount);
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
					"lmshop"=>$lmshop
				)
			);
			$this->smarty->display("csc_paotui_lmshop_product/index.html");
		}
		
		public function onAdd(){
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_csc_paotui_lmshop_product")->selectRow(array("where"=>"productid=".$productid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("csc_paotui_lmshop_product/add.html");
		}
		
		public function onSave(){
			$productid=get_post("productid","i");
			$data=M("mod_csc_paotui_lmshop_product")->postData();
			if(empty($data["title"])){
				$this->goAll("名称不能为空",1);
			}
			if(empty($data["price"])){
				$this->goAll("价格不能为空",1);
			}
			if($productid){
				M("mod_csc_paotui_lmshop_product")->update($data,"productid='$productid'");
			}else{
				M("mod_csc_paotui_lmshop_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$productid=get_post('productid',"i");
			$status=get_post("status","i");
			M("mod_csc_paotui_lmshop_product")->update(array("status"=>$status),"productid=$productid");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_csc_paotui_lmshop_product")->update(array("status"=>11),"productid=$productid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>