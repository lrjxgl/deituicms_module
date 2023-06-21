<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class cy2c_order_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			 
			$url="/module.php?m=cy2c_order_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$type=get("type","h");
			$order=" id ASC";
			switch($type){
				case "finish":
					$where=" status=4 ";
					$order=" id DESC";
					break;
				case "doing":
					$where=" status=2 ";
					break;
				case "send":
					$where=" status=3 ";
					break;
				case "cancel":
					$where=" status=8 ";
					$order=" id DESC";
					break;
				case "new":
					$where=" status=1 AND ispay=1 ";
					break;
				default:
					$where=" status in(0,1,2,3) AND ispay=1 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_cy2c_order_product")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$productids[]=$v["productid"];
					$ksids[]=$v["ksid"];
					$placeids[]=$v["placeid"];
				}
				$ks=MM("cy2c","cy2c_product_ks")->getListByIds($ksids);
				$pros=MM("cy2c","cy2c_product")->getListByIds($productids);
				$places=MM("cy2c","cy2c_place")->getListByIds($placeids);
				$statusList=MM("cy2c","cy2c_order_product")->statusList();
				foreach($data as $k=>$v){
					$v["product"]=$pros[$v["productid"]];
					$v["ks_title"]=$ks[$v["ksid"]]["title"].$ks[$v["ksid"]]["size"];
					$v["placeid_title"]=$places[$v["placeid"]]["title"];
					$v["status_name"]=$statusList[$v["status"]];
					$data[$k]=$v;
				}
			}
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
			$this->smarty->display("cy2c_order_product/index.html");
		}
		
		 
		public function onConfirm(){
			$id=get_post('id',"i");
			$row=M("mod_cy2c_order_product")->selectRow("id=".$id);
			if($row["ispay"]!=1){
				$this->goAll("该产品还未支付哦",1);
			}
			 if($row["status"]!=1){
				 $this->goAll("已经处理过了",1);
			 }
			M("mod_cy2c_order_product")->update(array("status"=>2),"id=$id");
			$this->goall("确认成功");
		}
		public function onSend(){
			$id=get_post('id',"i");
			$row=M("mod_cy2c_order_product")->selectRow("id=".$id);			 
			if($row["status"]!=2){
				$this->goAll("已经处理过了",1);
			}
			M("mod_cy2c_order_product")->update(array("status"=>3),"id=$id");
			$this->goall("配送成功");
		}
		
		public function onFinish(){
			$id=get_post('id',"i");
			$row=M("mod_cy2c_order_product")->selectRow("id=".$id);			 
			if($row["status"]!=3){
				$this->goAll("已经处理过了",1);
			}
			M("mod_cy2c_order_product")->update(array("status"=>4),"id=$id");
			$this->goall("上菜成功");
		}
		public function onCancel(){
			$id=get_post('id',"i");
			$row=M("mod_cy2c_order_product")->selectRow("id=".$id);
			if($row["ispay"]!=1){
				$this->goAll("该产品还未支付哦",1);
			}
			 if($row["status"]!=1){
				 $this->goAll("已经处理过了",1);
			 }
			M("mod_cy2c_order_product")->update(array("status"=>8),"id=$id");
			$this->goall("取消成功");
		}
		
	}

?>