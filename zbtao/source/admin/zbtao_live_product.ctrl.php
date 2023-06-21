<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class zbtao_live_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$type=get("type","h");
			switch($type){
				case "online":
					$where=" status=1 ";
					break;
				case "offline":
					$where=" status=2 ";
					break;
				case "all":
					$where=" status in(0,1,2)";
					break;
				default:
					$where=" status=0 ";
					break;
			}
			$url="/moduleadmin.php?m=zbtao_live_product&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("zbtao","zbtao_live_product")->Dselect($option,$rscount);
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
			$this->smarty->display("zbtao_live_product/index.html");
		}
		
		public function onAdd(){
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_zbtao_live_product")->selectRow(array("where"=>"productid=".$productid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("zbtao_live_product/add.html");
		}
		
		public function onSave(){
			$productid=get_post("productid","i");
			$data=M("mod_zbtao_live_product")->postData();
			if($productid){
				M("mod_zbtao_live_product")->update($data,"productid='$productid'");
			}else{
				M("mod_zbtao_live_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$productid=get_post('productid',"i");
			 
			$row=M("mod_zbtao_live_product")->selectRow("productid=".$productid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			M("mod_zbtao_live_product")->update(array("status"=>$status),"productid=$productid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onRecommend(){
			$productid=get_post('productid',"i");
			 
			$row=M("mod_zbtao_live_product")->selectRow("productid=".$productid);
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			M("mod_zbtao_live_product")->update(array("isrecommend"=>$status),"productid=$productid");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_zbtao_live_product")->update(array("status"=>11),"productid=$productid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>