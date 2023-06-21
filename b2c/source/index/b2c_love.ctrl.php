<?php
class b2c_loveControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$rscount=true;
		$per_page=0;
		$ids=M("love")->selectCols(array(
			"where"=>" userid=".$userid." AND tablename='mod_b2c_product' ",
			"fields"=>"objectid",
			"order"=>" id DESC",
			"limit"=>48
		));
		$list=[];
		if(!empty($ids)){
			$pros=MM("b2c","b2c_product")->getListByIds($ids);
			foreach($ids as $id){
				$list[]=$pros[$id];
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
		$this->smarty->display("b2c_love/my.html");
	}
	
}
?>