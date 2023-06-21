<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class youyao_shop_applyControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status=0 ";
			$url="/moduleadmin.php?m=youyao_shop_apply&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_youyao_shop_apply")->select($option,$rscount);
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
			$this->smarty->display("youyao_shop_apply/index.html");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_youyao_shop_apply")->selectRow("shopid=".$shopid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_youyao_shop_apply")->update(array("status"=>$status),"shopid=".$shopid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_youyao_shop_apply")->update(array("status"=>11),"shopid=".$shopid);
			$this->goAll("删除成功");
			 
		}
		
		
		public function onPass(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_youyao_shop_apply")->selectRow("shopid=".$shopid);
			if($row["status"]!=0){
				$this->goALl("已经处理过了",1);
				
			}
			M("mod_youyao_shop_apply")->begin();
			M("mod_youyao_shop_apply")->update(array(
				"status"=>1
			),"shopid=".$shopid);
			M("mod_youyao_shop")->insert(array(
				"shopname"=>$row["shopname"],
				"userid"=>$row["userid"],
				"nickname"=>$row["nickname"],
				"telephone"=>$row["telephone"],
				"address"=>$row["address"],
				"lat"=>$row["lat"],
				"lng"=>$row["lng"]
			));
			M("mod_youyao_shop_apply")->commit();
			$this->goAll("success");
		}
		public function onForbid(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_youyao_shop_apply")->selectRow("shopid=".$shopid);
			if($row["status"]!=0){
				$this->goALl("已经处理过了",1);
				
			}
			M("mod_youyao_shop_apply")->begin();
			M("mod_youyao_shop_apply")->update(array(
				"status"=>2
			),"shopid=".$shopid);
			 
			M("mod_youyao_shop_apply")->commit();
			$this->goAll("success");
		}
		
	}

?>