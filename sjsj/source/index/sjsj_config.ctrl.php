<?php
class sjsj_configControl extends skymvc{
	public function onDefault(){
		$scf=MM("sjsj","sjsj_config")->get();
		$this->smarty->goAssign(array(
			"scf"=>$scf
		));
	}
}