<?php
class zbliveModel extends model{
	public $table="mod_zblive";
	public function vdsize($vid){
		$vdList=array(
			0=>9/16,
			1=>9/16,
			2=>"1",
			3=>3/4
		);
		return $vdList[$vid];
	}
}