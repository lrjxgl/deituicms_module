<?php
class vr3d_jdo2oControl extends skymvc{
	
	public function onDefault(){
		
	}
	public function onPlace(){
		$placeid=get("placeid","i");
		$vr3d=M("mod_jdo2o_place_vr3d")->selectRow("placeid=".$placeid);
		$vrData=[
			images_site($vr3d["px"]),images_site($vr3d["nx"]),
			images_site($vr3d["py"]),images_site($vr3d["ny"]),
			images_site($vr3d["pz"]),images_site($vr3d["nz"])
		];
		$this->smarty->goAssign(array(
			"vr3d"=>$vr3d,
			"vrData"=>json_encode($vrData)
		));
		$this->smarty->display("vr3d_jdo2o/place.html");
		
	}
	
	public function onShop(){
		
	}
	
}
?>