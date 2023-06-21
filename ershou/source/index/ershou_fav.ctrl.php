<?php
class ershou_favControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$rscount=true;
		$per_page=0;
		$ids=M("fav")->selectCols(array(
			"where"=>" userid=".$userid." AND tablename='mod_ershou_product' ",
			"fields"=>"objectid",
			"order"=>" id DESC",
			"limit"=>48
		));
		$list=[];
		if(!empty($ids)){
			$pros=MM("ershou","ershou_product")->getListByIds($ids);
			foreach($ids as $id){
				$list[]=$pros[$id];
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page
		));
		$this->smarty->display("ershou_fav/my.html");
	}
	
}
?>