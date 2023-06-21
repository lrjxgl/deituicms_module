<?php
class cy2c_placeModel extends model{
	public $table="mod_cy2c_place";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)){
			return false;
		}
		$res=$this->select(array(
			"where"=>" placeid in("._implode($ids).") "
		));
		if($res){
			foreach($res as $rs){
				$data[$rs["placeid"]]=$rs;
			}
			return $data;
		}
		return false;
	}
	
	
	
}
?>