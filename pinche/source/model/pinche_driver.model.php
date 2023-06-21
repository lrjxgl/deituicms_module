<?php
class pinche_driverModel extends model{
	public $table="mod_pinche_driver";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" driverid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["driverid"]]=$rs;
			}
			
		}
		return $list;
	}
	public function getFree($lineid){
		$sql="
			select d.* from ".table("mod_pinche_driver_line")." as l
			left join ".table("mod_pinche_driver")." as d
			on l.driverid=d.driverid
			where l.lineid=".$lineid." AND l.status=1 
			order by l.freetime ASC
			limit 1
		";
		$res=$this->getRow($sql);
		return $res;
	} 
	
	public function setCode($dirver){
		$key="hsdirver".$dirver["dirverid"]."x".md5(time());
		$key=substr($key,0,32);
		$expire=3600*24*14;
		cache()->set($key,$dirver["dirverid"],$expire);
		setcookie("ck_pinche_hsdirver",$key,time()+$expire);
		return $key;
	}
	public function codeLogin(){
		$key=get_post("ck_pinche_hsdirver","h");
		if(!$key){
			$key=sql($_COOKIE["ck_pinche_hsdirver"]);
		}
		 
		if(empty($key)){
			return false;
		}
		$dirverid=cache()->get($key);
		
		$expire=3600*24*14;
		if($dirverid){
			$row=$this->selectRow("dirverid=".$dirverid);
			if($row){
				$_SESSION["ss_pinche_driverid"]=$dirverid;
				setcookie("ck_pinche_hsdirver",$key,time()+$expire);
				return true;
			}
		}
		
		return false; 
	}
	
	public function logout(){
		$key=get_post("ck_pinche_hsdirver","h");
		if(!$key){
			$key=sql($_COOKIE["ck_pinche_hsdirver"]);
		}
		cache()->del($key);
		$_SESSION["ss_pinche_driverid"]=null;
		unset($_SESSION["ss_pinche_driverid"]);
	}
	
}