<?php
class gread_partyModel extends model{
	public $table="mod_gread_party";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$res=$this->select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$list=[];
			foreach($res as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$list[$rs["id"]]=$rs;
			}
			return $list;
		}
		
	}
}