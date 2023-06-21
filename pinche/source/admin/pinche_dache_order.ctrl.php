<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_dache_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=pinche_dache_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_dache_order")->select($option,$rscount);
			if(!empty($data)){
				$uids=[];
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["nickname"]=$us[$v["userid"]]["nickname"];
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
			$this->smarty->display("pinche_dache_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_pinche_dache_order")->selectRow(array("where"=>"orderid=".$orderid));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("pinche_dache_order/show.html");
		}
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$row=M("mod_pinche_dache_order")->selectRow("orderid=".$orderid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_pinche_dache_order")->update(array("status"=>$status),"orderid=".$orderid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_pinche_dache_order")->update(array("status"=>11),"orderid=".$orderid);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>