<?php
class jieti_askModel extends model{
	public $table="mod_jieti_ask";
	public function __construct(){
		parent::__construct();
	}
	
	public function typeList(){
		return array(
			1=>array(
				"id"=>"1",
				"title"=>"默认￥0.01",
				"money"=>0.01,
				"checked"=>true
			),
			2=>array(
				"id"=>"2",
				"title"=>"加急￥1",
				"money"=>1
			)
	 
		);
	}
	
	public function Dselect($option,$rscount=true){
		$data=$this->select($option);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
		}
		return $data;
	}
}

?>