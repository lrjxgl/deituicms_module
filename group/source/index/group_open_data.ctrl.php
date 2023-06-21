<?php
class group_open_dataControl extends skymvc{
	
	public function onDefault(){
		
		
	}
	public function onList(){
		$start=0;
		$limit=12;
		$order=" productid DESC";
		$userid=M("login")->userid;
		$where=" userid=".$userid;
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_ershou_product")->select($option,$rscount);
		if(!empty($data)){
			foreach($data as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$data[$k]=$v;
			}
		}
		
		$this->smarty->goassign(array(
			"list"=>$data
		));
	}
	
	public function onShow(){
		$open_data=get("open_data","h");
		$ex=explode(":",$open_data);
		$tablename=sql($ex[0]);
		$id=intval($ex[1]);
		$data=[];
		switch($tablename){
			case "ershou_product":
				$data=MM("ershou","ershou_product")->getDataById($id);
				break;
		}
		$this->smarty->goAssign(array(
			"data"=>$data
		));
	}
	
}