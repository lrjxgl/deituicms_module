<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class recycle_shop_priceControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="/moduleshop.php?m=recycle_shop_price&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_recycle_shop_price")->select($option,$rscount);
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
			$this->smarty->display("recycle_shop_price/index.html");
		}
		
		public function onAdd(){
			$content=M("mod_recycle_shop_price")->selectOne(array(
				"where"=>" status in(0,1) AND shopid=".SHOPID,
				"fields"=>"content",
				"order"=>" id DESC"
			));
			$this->smarty->goassign(array(
				"content"=>$content
			));
			$this->smarty->display("recycle_shop_price/add.html");
		}
		
		public function onSave(){
		 
			$data=M("mod_recycle_shop_price")->postData();
			$data["shopid"]=SHOPID;
			$data["status"]=0;
			M("mod_recycle_shop_price")->insert($data);
			$this->goall("保存成功");
		}
		 
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_recycle_shop_price")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_recycle_shop_price")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>