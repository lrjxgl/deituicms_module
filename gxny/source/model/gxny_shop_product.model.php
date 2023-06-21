<?php
class gxny_shop_productModel extends model{
	public $table="mod_gxny_shop_product";
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
				 
				$data[$rs['id']]=$rs;
			}
			return $data;
		}
	}
	 
	 
}

?>