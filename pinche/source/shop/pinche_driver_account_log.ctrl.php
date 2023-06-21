<?php
class pinche_driver_account_logControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where="driverid=".DRIVERID ;
		$url="/moduleshop.php?m=pinche_driver_account_log";
		$limit=12;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_pinche_driver_account_log")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v["timeago"]=timeago($v["dateline"]);
				$v["type_name"]=$v["typeid"]==1?"收入":"支出";
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$account=MM("pinche","pinche_driver_account")->get(DRIVERID);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"account"=>$account
			)
		);
		$this->smarty->display("pinche_driver_account_log/index.html");
	}
	
}