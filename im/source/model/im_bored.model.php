<?php
class im_boredModel extends model{
	public $table="mod_im_bored";
	public function __construct(){
		parent::__construct();
	}
	
	public function Dselect($option,&$rscount=false){
		$res=$this->Select($option,$rscount);
		if($res){
			foreach($res as $key=>$rs){
				$rs["user_head"]=images_site($rs["user_head"]);
				$res[$key]=$rs;
			}
		}
		return $res;
	}
	
	public function updateUser($userid){
		$row=$this->selectRow("userid=".$userid);
		$addr=ipCity(ip());
		if(!empty($addr["city"])){
			$city=$addr["city"];
		}else{
			$city="全国";
		}
		$gps=gps_get();
		$lat=$gps['lat'];
		$lng=$gps['lng'];
		$user=M("user")->selectRow("userid=".$userid);
		$age=intval((time()-strtotime($user["birthday"]))/(3600*24*365));
		if($row){
			if(time()-$row["dateline"]<300){
				return false;
			}
			$this->update(array(
				"dateline"=>time(),
				"city"=>sql($city),
				"nickname"=>$user["nickname"],
				"user_head"=>$user["user_head"],
				"description"=>$user["description"],
				"lat"=>$lat,
				"lng"=>$lng,
				"gender"=>$user["gender"],
				"age"=>$age
			),"userid=".$userid);
		}else{
			
			$this->insert(array(
				"userid"=>$userid,
				"nickname"=>$user["nickname"],
				"user_head"=>$user["user_head"],
				"description"=>$user["description"],
				"dateline"=>time(),
				"city"=>sql($city),
				"lat"=>$lat,
				"lng"=>$lng,
				"gender"=>$user["gender"],
				"age"=>$age
			));
			
		}
		
	}
	
}

?>