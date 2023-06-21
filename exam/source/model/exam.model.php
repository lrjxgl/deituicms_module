<?php
class examModel extends model{
	public $table="mod_exam";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" exid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$arr=array();
			foreach($res as $rs){
				$arr[$rs["exid"]]=$rs;
			}
			return $arr;
		}
	}
	
}

?>