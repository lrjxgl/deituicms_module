<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class vipcard_logControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
			$where=" status in(0,1,2)";
			$url="/module.php?m=vipcard_log&a=default";
			$cardid=get("cardid","i");
			if($cardid){
				$where.=" AND cardid=".$cardid;
				$url.="&cardid=".$cardid;
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
			$data=M("mod_vipcard_log")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					 
					$cardids[]=$v["cardid"];
				}
				$cards=MM("vipcard","vipcard")->getListByIds($cardids);
				foreach($data as $k=>$v){
					$v["card"]=$cards[$v["cardid"]];
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
			$this->smarty->display("vipcard_log/index.html");
		}
		
		
	}

?>