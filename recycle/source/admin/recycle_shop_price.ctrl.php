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
			$type=get("type","h");
			switch($type){
				case "pass":
					$where=" status=1 ";
					break;
				case "forbid":
					$where=" status=2 ";
					break;
				default:
					$type="new";
					$where=" status in(0,1,2) ";
					break;	
			}
			
			$url="/moduleadmin.php?m=recycle_shop_price&type=".$type;
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
			if($data){
				foreach($data as $v){
					$shopids[]=$v["shopid"];
				}
				$statusList=array(
					0=>"待审核",
					1=>"已通过",
					2=>"已禁止"
				);
				$shops=MM("recycle","recycle_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["shop"]=$shops[$v["shopid"]];
					$v["status_name"]=$statusList[$v["status"]];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"type"=>$type,
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("recycle_shop_price/index.html");
		}
		
		public function onPass(){
			$id=get_post('id',"i");
			$row=M("mod_recycle_shop_price")->selectRow("id=".$id);
			M("mod_recycle_shop_price")->update(array("status"=>1),"id=".$id);
			$this->goAll("审核通过");			
		} 
		public function onForbid(){
			$id=get_post('id',"i");
			$row=M("mod_recycle_shop_price")->selectRow("id=".$id);
			M("mod_recycle_shop_price")->update(array("status"=>2),"id=".$id);
			$this->goAll("禁止成功");			
		}
		 
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_recycle_shop_price")->selectRow("id=".$id);
			 
			M("mod_recycle_shop_price")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>