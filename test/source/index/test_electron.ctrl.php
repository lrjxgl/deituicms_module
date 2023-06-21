<?php
class test_electronControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$data=json_encode($_POST);
		file_put_contents("electron.txt",$data);
		echo "success";
	}
	
}

?>