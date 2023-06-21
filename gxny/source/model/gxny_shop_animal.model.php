<?php
class gxny_shop_animalModel extends model{
	public $table="mod_gxny_shop_animal";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids,$fields="*"){
		$option=array(
			"where"=>" id in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]); 
				$data[$rs['id']]=$rs;
			}
			return $data;
		}
	}
	 
	 
}

?>