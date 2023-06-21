<?php
class h5video_pluginModel extends model{
	public $table="mod_h5video_plugin";
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
	public function pluginList(){
		$res=$this->select();
		if($res){
			$data=array();
			foreach($res as $rs){
				$data[$rs["id"]]=$rs["title"];
			}
			return $data;
		}
		 
	}
	
}

?>