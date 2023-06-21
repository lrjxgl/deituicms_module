<?php
class test_productControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$file="attach/demo/product/data.txt";
		$table="mod_b2c_product";
		$c=file_get_contents($file);
		$res=json_decode($c,true);
		foreach($res as $rs){
			$id=M($table)->insert(array(
				"title"=>$rs["title"],
				"price"=>rand(10,1000),
				"imgurl"=>$rs["imgurl"],
				"status"=>1,
				"isrecommend"=>rand(0,1),
				"total_num"=>1000,
				"createtime"=>date("Y-m-d H:i:s")
			));
			M("{$table}_data")->insert(array(
				"id"=>$id
			));
		}
		 
		echo "success";
	}
	
}
?>