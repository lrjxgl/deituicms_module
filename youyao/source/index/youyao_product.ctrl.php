<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class youyao_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=youyao_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_youyao_product")->select($option,$rscount);
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
			$this->smarty->display("youyao_product/index.html");
		}
		
		public function onList(){
			$where=" status=1 ";
			$url="/module.php?m=youyao_product&a=list";
			$keyword=get("keyword","h");
			if(!empty($keyword)){
				$where.=" AND title like '%".$keyword."%' ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("youyao","youyao_product")->Dselect($option,$rscount);
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
			$this->smarty->display("youyao_product/index.html");
		}
		
		public function onShow(){
			$productid=get_post("productid","i");
			$data=M("mod_youyao_product")->selectRow(array("where"=>"productid=".$productid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("youyao_product/show.html");
		}
		
		public function onMy(){
			$userid=M("login")->userid;
			$shop=MM("youyao","youyao_shop")->getShopByUserid($userid);
			if(empty($shop)){
				$this->goAll("暂无权限",1);
			}
			$where=" status in(0,1,2) AND shopid=".$shop["shopid"];
			$url="/module.php?m=youyao_product&a=my";
			$keyword=get("keyword","h");
			if(!empty($keyword)){
				$where.=" AND title like '%".$keyword."%' ";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=MM("youyao","youyao_product")->Dselect($option,$rscount);
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
			$this->smarty->display("youyao_product/my.html");
		}
		
		public function onAdd(){
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_youyao_product")->selectRow(array("where"=>"productid=".$productid));
				$data["trueimgurl"]=images_site($data["imgurl"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("youyao_product/add.html");
		}
		
		public function onSave(){
			$userid=M("login")->userid;
			$shop=MM("youyao","youyao_shop")->getShopByUserid($userid);
			if(empty($shop)){
				$this->goAll("暂无权限",1);
			}
			$productid=get_post("productid","i");
			$data=M("mod_youyao_product")->postData();
			if($productid){
				$row=M("mod_youyao_product")->selectRow("productid=".$productid);
				if($shop["shopid"]!=$row["shopid"]){
					$this->goAll("暂无权限",1);
				}
				M("mod_youyao_product")->update($data,"productid=".$productid);
			}else{
				$data["shopid"]=$shop["shopid"];
				M("mod_youyao_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$productid=get_post('productid',"i");
			$row=M("mod_youyao_product")->selectRow("productid=".$productid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_youyao_product")->update(array("status"=>$status),"productid=".$productid);
			$this->goall("状态修改成功",0,$status);
		}
		public function onNum(){
			$productid=get_post('productid',"i");
			$row=M("mod_youyao_product")->selectRow("productid=".$productid);
			if($row["total_num"]==0){
				$status=1;
			}else{
				$status=0;
			}
			 
			M("mod_youyao_product")->update(array("total_num"=>$status),"productid=".$productid);
			$this->goall("状态修改成功",0,$status);
		}
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_youyao_product")->update(array("status"=>11),"productid=".$productid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>