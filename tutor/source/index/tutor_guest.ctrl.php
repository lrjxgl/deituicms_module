<?php
class tutor_guestControl extends skymvc{
	
	public function onDefault(){
		 
		CC("kefu","kefu_msg_index")->smarty->template_dir="module/kefu/themes/index";
		CC("kefu","kefu_msg_index")->smarty->assign("skins","module/kefu/themes/index/");
		//$this->smarty->assign("ftnav","guest");
		//CC("kefu","kefu_msg_index")->smarty->assign("ftnav",$this->smarty->fetch("ftnav.html"));
		CC("kefu","kefu_msg_index")->onDefault();
		 
	}
	
	public function onShow(){
		CC("kefu","kefu_msg_index")->onShow();
	}
	
}