<?php
class imgdiy_categoryModel extends model{
	public $table="mod_imgdiy_category";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function id_title($option=array()){
		$res=$this->select($option);
		if($res){
			foreach($res as $rs){
				$data[$rs['catid']]=$rs['title'];
			}
			return $data;
		}
		 
	} 
	
}