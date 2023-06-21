<?php
/**
 * 无聊的人
 */
class im_boredControl extends skymvc{
	public $usrList;
	public function __construct(){
		parent::__construct();
	}
	
	 
	
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		MM("im","im_bored")->updateUser($userid);
		$type=get("type","h");
		$fields="*";
		$order=" dateline DESC ";
		$etime=0;//time()-3600*24*3;
		switch($type){
			case "city":
				$addr=ipCity(ip());
				if(!empty($addr["city"])){
					$city=$addr["city"];
				}else{
					$city="中国";
				}
				$where="  city='".sql($city)."' AND dateline>".$etime." ";
				break;
			case "near":
				$addr=ipCity(ip());
				if(!empty($addr["city"])){
					$city=$addr["city"];
				}else{
					$city="中国";
				}
				$gps=gps_get();
				$lat=$gps['lat'];
				$lng=$gps['lng'];
				 
				if($lat && $lng){
					$fields.=",".' ROUND(  
						6378.138 * 2 * ASIN(  
							SQRT(  
								POW(  
									SIN(  
										(  
											'.$lat.' * PI() / 180 - lat * PI() / 180  
										) / 2  
									),  
									2  
								) + COS('.$lat.' * PI() / 180) * COS(lat * PI() / 180) * POW(  
									SIN(  
										(  
											'.$lng.' * PI() / 180 - lng * PI() / 180  
										) / 2  
									),  
									2  
								)  
							)  
						)  
					) AS distance_num  ';
					$order=" distance_num ASC";
					
				} 
				$where="   dateline>".$etime." ";
				break;
			default:
				$where="   dateline>".$etime." ";
				break;
		}
		$option=array(
			"order"=>$order,
			"limit"=>100,
			"where"=>$where,
			"fields"=>$fields
		);
		$userList=MM("im","im_bored")->Dselect($option);
		//Ai聊天
		/*
		$aiList=M("user")->getUserByIds(array(
			"1511","1510"
		));
		*/
		if($userList){
			foreach($userList as $k=>$v){
				if($v["userid"]==$userid){
					//unset($userList[$k]);
				}
			}
		}
		/*
		if($userList && $aiList){
			$userList=array_merge($aiList,$userList);
		}
		*/	
		$this->smarty->goAssign(array(
			"userList"=>$userList
		));
		$this->smarty->display("im_bored/index.html");
	}
	
}