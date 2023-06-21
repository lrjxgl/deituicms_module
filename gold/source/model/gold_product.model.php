<?php
class gold_productModel extends model{
	public $table="mod_gold_product";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($ops,$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$res[$k]=$v;
			}
		}
		return $res;
	}
	public function getListByIds($ids,$feilds="id,title,imgurl"){
		$ids=array_unique($ids);
		$rss=$this->select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$data[$rs["id"]]=$rs;
				 
			}
			return $data;
		}
	}
}

?>