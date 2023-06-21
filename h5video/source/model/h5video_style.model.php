<?php
class h5video_styleModel extends model{
	public $table="mod_h5video_style";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" id in("._implode($ids).") "
		));
		if($res){
			$data=array();
			foreach($res as $rs){
				$data[$rs["id"]]=$rs;
			}
			return $data;
		}
	}
}
?>