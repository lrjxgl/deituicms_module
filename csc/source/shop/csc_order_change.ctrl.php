<?php
class csc_order_changeControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$type=get("type","h"); 
		$where=" shopid=".SHOPID;
		$url="/moduleshop.php?m=csc_order_change";
		$limit=24;
		$start=get("per_page","i");
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_csc_order_change")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$oids[]=$v["orderid"];
			}
			$ods=MM("csc","csc_order")->getListByIds($oids);
			foreach($data as $k=>$v){
				$p=$ods[$v['orderid']];
				$p["nmoney"]=$v["money"];
				$p["ncontent"]=$v["content"];
				$data[$k]=$p;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"type"=>$type
		));
		$this->smarty->display("csc_order_change/index.html");
	}
	
}
?>