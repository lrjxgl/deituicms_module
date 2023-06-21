<?php
class recycle_shopControl extends skymvc{
	public function onDefault(){
		$shop=M("mod_recycle_shop")->selectRow("shopid=".SHOPID);
		$shop["trueimgurl"]=images_site($shop["imgurl"]);
		$this->smarty->goAssign(array(
			"shop"=>$shop
		));
		$this->smarty->display("recycle_shop/index.html");
	}
	
	public function onSave(){
		$arr=array("title","imgurl","description","address","lat","lng");
		$data=[];
		foreach($arr as $k){
			$data[$k]=post($k,"h");
		}
		if(!checkFileDir($data["imgurl"])){
			unset($data["imgurl"]);
		}
		
		$data=M("mod_recycle_shop")->update($data,"shopid=".SHOPID);
		$this->goAll("保存成功",1);
	}
}
?>