<?php
class mdish_viewControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		 
		
	}
	
	public function onMy(){
		$userid=M("login")->userid;
	 
		$res=M("mod_mdish_view")->select(array(
			"where"=>"userid=".$userid,
			"fields"=>"productid,shopid",
			"order"=>"id ASC"
		));
		$pids=array();
		$spids=array();
		$list=array();
		if($res){
			foreach($res as $rs){
				$pids[]=$rs["productid"];
				$spids[]=$rs["shopid"];
			}
			$pros=MM("mdish","mdish_product")->getListByIds($pids);
			$shops=MM("mdish","mdish_shop")->getListByIds($spids,"shopid,title");
			 
			foreach($res as $rs){
				$v=$pros[$rs["productid"]];
				$v["shop"]=$shops[$rs["shopid"]];
				$list[]=$v;
				
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("mdish_view/my.html");
		
	}
	
	 
}
?>