<?php
class csc_vip_userModel extends model{
	public $table="mod_csc_vip_user";
	public function __construct(){
		parent::__construct();
	}
	public function get($userid,$shopid=0){
		$row=$this->selectRow("userid=".$userid." AND shopid=".$shopid);
		if($row){
			$vip=M("mod_csc_vip")->selectRow("vipid=".$row["vipid"]);
			$row["etime"]=date("Y-m-d H:i:s",$row["etime"]);
			$row["title"]=$vip["title"];
			$row["discount"]=$vip["discount"];
			$row["description"]=nl2br($vip["description"]);
		}	
		return $row;
	}
	public function add($ops){
		$userid=$ops["userid"];
		$row=$this->selectRow("userid=".$userid." AND shopid=".$ops["shopid"]);
		if(!$row){
			if($ops["vip_day"]=='month'){
				$etime=time()+3600*24*30;
			}else{
				$etime=time()+3600*24*365;
			}
			$this->insert(array(
				"userid"=>$userid,
				"vipid"=>$ops["vipid"],
				"etime"=>$etime,
				"shopid"=>$ops["shopid"]
			));
		}else{
			if($ops["vip_day"]=='month'){
				$etime=$row["etime"]+3600*24*30;
			}else{
				$etime=$row["etime"]+3600*24*365;
			}
			$this->update(array(
				"userid"=>$userid,
				"vipid"=>$ops["vipid"],
				"etime"=>$etime
			),"id=".$row["id"]);
		}
	}
	
}