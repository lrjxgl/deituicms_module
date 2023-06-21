<?php
class im_giftModel extends model{
	public $table="mod_im_gift";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$res=$this->select(array(
			"where"=>" giftid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$list=array();
			foreach($res as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$list[$rs["giftid"]]=$rs;
			}
			return $list;
		}
	}
	
}