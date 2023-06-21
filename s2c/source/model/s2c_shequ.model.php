<?php
class s2c_shequModel extends model{
	public $table="mod_s2c_shequ";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids){
		$rss=$this->select(array(
			"where"=>" scid in("._implode($ids).") "
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				$data[$rs["scid"]]=$rs;
			}
			return $data;
		}
	}
}