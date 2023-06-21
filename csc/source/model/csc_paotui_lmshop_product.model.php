<?php
class csc_paotui_lmshop_productModel extends model{
	public $table="mod_csc_paotui_lmshop_product";
	public function __construct(){
		parent::__construct();
	}
	 
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$option=array(
			"where"=>" productid in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				
				$data[$rs['productid']]=$rs;
			}
			return $data;
		}
	}
}