<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class teamwork_shop_product_itemControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
		}
		
		public function onDefault(){
			
			$url="/admin.php?m=teamwork_shop_product_item&a=default";
			$type=get("type",'h');
			switch($type){
				case "finish":
					break;
				default:
					$where=" status in(0,1,2,3,4) ";
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_teamwork_shop_product_item")->select($option,$rscount);
			$statusList=M("mod_teamwork_shop_product_item")->statusList();
			$typeList=M("mod_teamwork_shop_product_item")->typeList();
			$orderList=M("mod_teamwork_shop_product_item")->orderList();
			if($data){
				foreach($data as $k=>$v){
					$v['status_name']=$statusList[$v['status']];
					$v['typeid_name']=$typeList[$v['typeid']];
					$v['orderindex_name']=$orderList[$v['orderindex']];
					$data[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$this->smarty->goassign(
				array(
					"data"=>$data,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"per_page"=>$per_page,
				)
			);
			$this->smarty->display("teamwork_shop_product_item/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_teamwork_shop_product_item")->selectRow(array("where"=>"id={$id}"));
				
			}
			$statusList=M("mod_teamwork_shop_product_item")->statusList();
			$typeList=M("mod_teamwork_shop_product_item")->typeList();
			$orderList=M("mod_teamwork_shop_product_item")->orderList();
			$this->smarty->goassign(array(
				"data"=>$data,
				"statusList"=>$statusList,
				"typeList"=>$typeList,
				"orderList"=>$orderList
			));
			$this->smarty->display("teamwork_shop_product_item/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_teamwork_shop_product_item")->postData();
			if($id){
				M("mod_teamwork_shop_product_item")->update($data,"id='$id'");
			}else{
				M("mod_teamwork_shop_product_item")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_teamwork_shop_product_item")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_teamwork_shop_product_item")->update(array("status"=>99),"id=$id");
			$this->goall("删除成功",0);
		}
		
		
	}

?>