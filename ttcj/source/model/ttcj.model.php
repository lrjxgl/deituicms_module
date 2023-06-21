<?php
class ttcjModel extends model{
	public $table="mod_ttcj";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids){
		$res=$this->select(array(
			"where"=>" cjid in("._implode($ids).") ",
			"fields"=>"cjid,title,imgurl"
		));
		if($res){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['cjid']]=$rs;
			}
			return $data;
		}
	}
}
?>