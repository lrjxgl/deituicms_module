<?php
class pdd_statControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		 
		$this->smarty->display("pdd_stat/index.html");
	}
	
	public function onData(){
		$time=strtotime(date("Y-m-d 00:00:00"));
		for($i=7;$i>=0;$i--){
			$labels[]=date("m月d",$time-$i*3600*24);
		}
		for($i=7;$i>=0;$i--){
			$h=date("Y-m-d",$time-$i*3600*24);
		 
			$sql="select COUNT(*) from ".table('mod_pdd_order')." where   createtime like '".$h."%'";
			 
			$orderMoneys[]=$count=M("mod_pdd_order")->getOne($sql);
			 
		}
		$this->smarty->goAssign(array(
			"labels"=>$labels,
			
			"orderMoneys"=>$orderMoneys,
			"total"=>$total
		));
		$this->smarty->display("pdd_stat/index.html");
	}
	
}