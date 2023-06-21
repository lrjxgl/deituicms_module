<?php
class house_tuan_ratyControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	public function onSave(){
		$userid=M("login")->userid;
		$data=M("mod_house_tuan_raty")->postData();
		$data["userid"]=$userid;
		if(empty($data["raty_content"])){
			$this->goAll("请说点什么吧",1);
		}
		$row=M("mod_house_tuan_raty")->selectRow("userid=".$userid." AND tid=".$data["tid"]);
		if($row){
			$this->goAll("你已经评价过了",1);
		}
		M("mod_house_tuan_raty")->insert($data);
		M("mod_house_tuan_user")->update(array(
			"israty"=>1		 
		),"userid=".$userid." AND tid=".$data["tid"]);
		$this->goAll("评价成功");
	}
	
}