<?php
class dzp_eventModel extends model{
	public $table="mod_dzp_event";
	public function __construct(){
		parent::__construct();
	}
	
	 
 
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$res=$this->select(array("where"=>"eventid in("._implode($ids).")"));
		if($res){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['eventid']]=$rs;
			}
			return $data;
		}
	}
}