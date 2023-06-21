<?php
class collect_ipModel extends model{
	public $table="mod_collect_ip";
	public function __construct(){
		parent::__construct();
	}
	public function getIp(){
		$key="mod_collect_ip_getip";
		if(!$ips=cache()->get($key)){
			$ips=$this->selectCols(array(
				"where"=>" status=1 ",
				"fields"=>"ip"
			));
			cache()->set($key,$ips,60);
		}
		$ips=$this->selectCols(array(
			"where"=>" status=1 ",
			"fields"=>"ip"
		));
		if($ips){
			if(count($ips)==1){
				return false;
			}
			return $ips[rand(0,count($ips)-1)];
		}
	}
}
?>