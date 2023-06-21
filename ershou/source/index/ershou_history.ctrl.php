<?php
class ershou_historyControl extends skymvc{
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$ids=M("mod_ershou_history")->selectCols(array(
			"where"=>" userid=".$userid." AND tablename='ershou_product' ",
			"order"=>" createtime DESC ",
			"limit"=>48,
			"fields"=>" objectid"
		));
		$list=[];
		if(!empty($ids)){
			$pros=MM("ershou","ershou_product")->getListByIds($ids);
			foreach($ids as $id){
				$list[]=$pros[$id];
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("ershou_history/index.html");
	}
}