<?php
class csc_paotui_lmshopModel extends model{
	public $table="mod_csc_paotui_lmshop";
	public function __construct(){
		parent::__construct();
	}
	 
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$option=array(
			"where"=>" lmid in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				
				$data[$rs['lmid']]=$rs;
			}
			return $data;
		}
	}
}