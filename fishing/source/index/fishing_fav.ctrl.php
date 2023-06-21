<?php
class fishing_favControl extends skymvc{
	public function onDefault(){
		
	}
	
	public function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$placeids=M("mod_fishing_fav")->selectCols(array(
			"where"=>"userid=".$userid,
			"fields"=>"placeid"
		));
		$list=MM("fishing","fishing_place")->getListByIds($placeids);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("fishing_fav/my.html");
	}
	
	public function onGet(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$placeid=get("placeid","i");
		$row=M("mod_fishing_fav")->selectRow("userid=".$userid." AND placeid=".$placeid);
		$isFav=0;
		if($row){
			$isFav=1;
		}
		$this->goAll("success",0,["isFav"=>$isFav]);
	}
	
	
	public function onToggle(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$placeid=get("placeid","i");
		$row=M("mod_fishing_fav")->selectRow("userid=".$userid." AND placeid=".$placeid);
		$isFav=0;
		if($row){
			$isFav=0;
			M("mod_fishing_fav")->delete("userid=".$userid." AND placeid=".$placeid);
		}else{
			$isFav=1;
			M("mod_fishing_fav")->insert([
				"userid"=>$userid,
				"placeid"=>$placeid
			]);
		}
		$this->goAll("success",0,["isFav"=>$isFav]);
	}
}