<?php
class pdd_productModel extends model{
	public $table="mod_pdd_product";
	public function __construct(){
		parent::__construct();
	}
	
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$res[$k]=$v;
			}
			return $res;
		}
	}
	
	public function getListByIds($ids,$fields="id,title,price,lower_price,pt_price,pt_open,buy_num,pt_min,total_num,market_price,imgurl"){
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