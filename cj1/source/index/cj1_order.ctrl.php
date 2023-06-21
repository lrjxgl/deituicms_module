<?php
class cj1_orderControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$url="/module.php?m=cj1_order&a=my";
		$limit=20;
		$start=get("per_page","i");
		$iswin=get('iswin','i');
		if($iswin){
			$where.=" AND iswin=1 ";
			$url.="&iswin=1";
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" orderid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_cj1_order")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$ids[]=$v['objectid'];
			}
			$cjs=M("mod_cj1")->select(array("where"=>"id in("._implode($ids).")"));
			foreach($cjs as $v){
				$ccs[$v['id']]=$v;
			}
			foreach($data as $k=>$v){
				$data[$k]=$ccs[$v['objectid']];
			}
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("order/my.html");
	}
	
}
?>