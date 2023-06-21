<?php
class cy2c_placeorderControl extends skymvc{
	
	public function onDefault(){
		$placeorder=MM("cy2c","cy2c_placeorder")->get(PLACEID);
	 
		$list=M("mod_cy2c_order_product")->select(array(
			"where"=>" poid=".$placeorder["poid"],
			"order"=>"id DESC"
		));
		 
		if(!empty($list)){
			foreach($list as $v){
				$ids[]=$v["productid"];
			}
			$res=MM("cy2c","cy2c_product")->getListByIds($ids,"id,title,imgurl,buy_num,price");
			$statusList=MM("cy2c","cy2c_order_product")->statusList();
			foreach($list as $k=>$v){
				$p=$res[$v["productid"]];
				$v["title"]=$p["title"];
				$v["imgurl"]=$p["imgurl"];
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		}
		
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("cy2c_placeorder/index.html");
	}
	
}