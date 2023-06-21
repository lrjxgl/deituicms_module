<?php
class b2c_historyControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$ids=M("mod_b2c_history")->selectCols(array(
			"where"=>" userid=".$userid." AND tablename='b2c_product' ",
			"order"=>" createtime DESC ",
			"limit"=>48,
			"fields"=>" objectid"
		));
		$list=[];
		if(!empty($ids)){
			$pros=MM("b2c","b2c_product")->getListByIds($ids);
			foreach($ids as $id){
				$list[]=$pros[$id];
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("b2c_history/index.html");
	}
}