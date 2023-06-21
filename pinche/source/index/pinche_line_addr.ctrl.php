<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pinche_line_addrControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=pinche_line_addr&a=default";
			$lineid=get("lineid","i");
			$stype=get("stype","i");
			if($lineid){
				$where.=" AND lineid=".$lineid;
			}
			if($stype){
				$where.=" AND stype=".$stype;
			}
			$limit=200;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" addrid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_pinche_line_addr")->select($option,$rscount);
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
			$this->smarty->display("pinche_line_addr/index.html");
		}
		
		 
		
		
	}

?>