<?php
class gread_olprintControl extends skymvc{
	
	public function onInit(){
		$a=get("a","h");
		if(!in_array($a,array("openbind","openbindsave"))){
			$a=str_replace("//","",$a);
			if(empty($a) || $a=='default'){
				$a="index";
			}
			$this->smarty->display("gread_olprint/".$a.".html");
		}
		
	}
	
	public function onOpenBind(){
		$row=M("mod_olprint_openbind")->selectRow(" tablename='gread_shop' AND objectid=".SHOPID." ");
		$this->smarty->goAssign(array(
			"bind"=>$row
		));
		$this->smarty->display("gread_olprint/openbind.html");
	}
	public function onOpenbindsave(){
		$shopname=get("shopname","h");
		$description=get("description","h");
		$fshop=M("mod_gread_shop")->selectRow("shopid=".SHOPID);
		 
		$shop=M("mod_olprint_shop")->selectRow("shopname='".$shopname."' ");
		if($shop){
			$row=M("mod_olprint_openbind")->selectRow(" tablename='gread_shop' AND objectid=".SHOPID." ");
			if($row){
				$this->goAll("已经申请过了",1);
			}else{
				M("mod_olprint_openbind")->insert(array(
					"tablename"=>"gread_shop",
					"objectid"=>SHOPID,
					"dateline"=>time(),
					"description"=>$description,
					"shopid"=>$shop["shopid"],
					"title"=>$fshop["title"],
					"shopname"=>$shop["shopname"]
				));
				$this->goAll("申请成功");
			}
		}else{
			$this->goAll("商家不存在",1);
		}
	}
	
	
}
