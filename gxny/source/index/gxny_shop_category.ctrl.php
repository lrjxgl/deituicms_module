<?php
class gxny_shop_categoryControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onCdList(){
		$catid=get("catid","i");
		$cat=M("mod_gxny_shop_category")->selectRow("catid=".$catid);
		$cdList=explode(",",$cat["caidan"]);
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"cdList"=>$cdList
			)
		));
		
	}
	
}