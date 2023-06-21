<?php
class fxaControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	public function onDefault(){
		
		$this->smarty->display("fxa/index.html");
	}
	
	public function onOrder7(){
		$time=strtotime(date("Y-m-d 11:59:59"));
		for($i=7;$i>=0;$i--){
			$labels[]=date("m月d",$time-$i*3600*24);
		}
		for($i=7;$i>=0;$i--){
			$h=date("Y-m-d",$time-$i*3600*24);		 
			$sql="select COUNT(*) from ".table('mod_fxa_order')." where ispay=1 AND  createtime like '".$h."%'";			 
			$orderMoneys[]=$count=M("mod_fxa_order")->getOne($sql);			 
		}
		$this->smarty->goAssign(array(
			"labels"=>$labels,			
			"orderMoneys"=>$orderMoneys,
			"total"=>$total
		));
	}
	public function onOrder30(){
		$t = date('Y-m');
		for($i=12;$i>=0;$i--){
			if($i==0){
				$labels[]=date("Y年m月");
			}else{
				$labels[]=date("Y年m月",strtotime("-$i month"));
			}
			
		}
		for($i=12;$i>=0;$i--){
			if($i==0){
				$month=date("Y-m");	
			}else{
				$month=date("Y-m",strtotime("-$i month"));	
			}
				 
			$sql="select COUNT(*) from ".table('mod_fxa_order')." where ispay=1 AND  createtime like '".$month."%'";			 
			$orderMoneys[]=$count=M("mod_fxa_order")->getOne($sql);			 
		}
		$this->smarty->goAssign(array(
			"labels"=>$labels,			
			"orderMoneys"=>$orderMoneys,
			"total"=>$total
		));
	}
}