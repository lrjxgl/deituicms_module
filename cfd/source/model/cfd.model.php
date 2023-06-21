<?php
class cfdModel extends model{
	public $table="mod_cfd";
	public function __construct(){
		 
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)) return false;
		$res=$this->select(array(
			"where"=>"cfdid in("._implode($ids).")",
			"fields"=>$fields
		));
		if($res){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['cfdid']]=$rs;
			}
			return $data;
		}
	}
}
?>