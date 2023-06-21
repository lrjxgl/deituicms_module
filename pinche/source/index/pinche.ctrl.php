<?php
class pincheControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		//广告
		$fromapp=get("fromapp");
		switch($fromapp){
			case "uniapp":
				$flashList=M("ad")->listByNo("uniapp-pinche-index");
				$adList=M("ad")->listByNo("uniapp-pinche-ad");
				$navList=M("ad")->listByNo("uniapp-pinche-nav"); 
				break;
			default:
				$flashList=M("ad")->listByNo("wap-pinche-index");
				$adList=M("ad")->listByNo("wap-pinche-ad");
				$navList=M("ad")->listByNo("wap-pinche-nav"); 
				break;
		}
		$this->smarty->goAssign(array(
			"flashList"=>$flashList,
			"adList"=>$adList,
			"navList"=>$navList
		));
		$this->smarty->display("index.html");
	}
	
	 
	
	public function onindexdata(){
		$userid=M("login")->userid;
		$config=M("mod_pinche_config")->selectRow("1");
		//判断是白天还是黑夜
		$baiTime=MM("pinche","pinche_line")->getBaiTime();
		$lineList=M("mod_pinche_line")->select(array(
			"where"=>" status=1 "
		));
		if(!empty($lineList)){
			foreach($lineList as $k=>$v){
				$v["basemoney"]=$baiTime?$v["bai_money"]:$v["hei_money"];
				$lineList[$k]=$v;
			}
		}
		$ppList=M("mod_pinche_people")->select(array(
			"where"=>" status=1 AND userid=".$userid
		)); 
		echo json_encode(array(
			"error"=>0,
			"message"=>"success",
			"data"=>array(
				"lineList"=>$lineList,
				"ppList"=>$ppList,
				"baiTime"=>$baiTime
				 
			)
		));
	}
	
	public function onHouche(){
		$order=MM("pinche","pinche_order")->selectRow(array(
			"where"=>"ispay=1 AND status<3 ",
			"order"=>" orderid DESC"
		));
		$group=$unpayorder=$driver=$line=$userList=false;
		if($order){
			if($order["gid"]){
				$group=MM("pinche","pinche_order")->selectRow("gid=".$order["gid"]);
			}
			if($order["driverid"]){
				$driver=MM("pinche","pinche_driver")->selectRow("driverid=".$order["driverid"]);
			}
			$line=MM("pinche","pinche_line")->selectRow("lineid=".$order["lineid"]);
		}else{
			$unpayorder=MM("pinche","pinche_order")->selectRow("ispay=0 AND status=0");
		}
		if($group){
			$uids=MM("pinche","pinche_order")->selectCols(array(
				"fields"=>"userid",
				"where"=>" gid=".$group["gid"]
			));
			$userList=M("user")->getUserByIds($uids);
		}
		$this->smarty->goAssign(array(
			"order"=>$order,
			"group"=>$group,
			"unpayorder"=>$unpayorder,
			"driver"=>$driver,
			"line"=>$line,
			"userList"=>$userList
		));
		$this->smarty->display("pinche/houche.html");
	}
	
}