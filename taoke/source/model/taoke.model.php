<?php
class taokeModel extends model{
	
	public $table="mod_taoke";
	
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function xfromList(){
		return array(
			"taobao"=>"淘宝",
			"tmall"=>"天猫",
			
			"pdd"=>"拼多多",
			"jd"=>"京东",
			"vip"=>"唯品会"
		);
	}
	
	public function getListByIds($ids,$w=""){
		$res=$this->select(array(
			"where"=>" id in("._implode($ids).") ".$w
		));
		$data=array();
		foreach($res as $rs){
			$rs["imgurl"]=images_site($rs["imgurl"]);
			$data[$rs['id']]=$rs;
		}
		return $data;
	}
	
	
}
?>