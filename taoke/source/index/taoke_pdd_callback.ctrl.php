<?php
class taoke_pdd_callbackControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		echo "call";
	}
}