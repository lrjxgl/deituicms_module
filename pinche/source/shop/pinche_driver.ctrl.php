<?php
class pinche_driverControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	
	public function onInfo(){
		$driver=M("mod_pinche_driver")->selectRow("driverid=".DRIVERID);
		$driver["carpic"]=images_site($driver["carpic"]); 
		$this->smarty->goAssign(array(
			"driver"=>$driver
		));
		$this->smarty->display("pinche_driver/info.html");
	} 
	
	public function onhouche(){
		
		$driver=M("mod_pinche_driver")->selectRow("driverid=".DRIVERID);
		if(empty($driver)){
			$this->goAll("您不是司机",1);
		}
		$group=MM("pinche","pinche_group")->selectRow("driverid=".$driver["driverid"]." AND status=1 ");
		if(empty($group)){
			$this->goAll("等待成团中",1);
		}
		$orderList=MM("pinche","pinche_order")->select(array(
			 
			"where"=>" gid=".$group["gid"]
		));
		$uorder=array();
		if($orderList){
			foreach($orderList as $v){
				$uids[]=$v["userid"];
				$uorder[$v["userid"]]=$v;
			}
		}
		$userList=M("user")->getUserByIds($uids,"user_head,userid,nickname,telephone");
		if($userList){
			foreach($userList as $k=>$v){
				$v["start_lat"]=$uorder[$v["userid"]]["start_lat"];
				$v["start_lng"]=$uorder[$v["userid"]]["start_lng"];
				 
				$userList[$k]=$v;
			}
		}
		$line=MM("pinche","pinche_line")->selectRow("lineid=".$group["lineid"]);
		$this->smarty->goAssign(array(
			"userList"=>$userList,
			"line"=>$line,
			"group"=>$group,
			"driver"=>$driver
		));
		$this->smarty->display("pinche_driver/houche.html");
	}
	
}