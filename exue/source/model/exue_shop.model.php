<?php
class exue_shopModel extends model{
	public $table="mod_exue_shop";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid,$fields="*"){
		$shop=$this->selectRow(array(
			"where"=>" shopid=".$shopid,
			"fields"=>$fields
		));
		if(isset($shop["imgurl"])){
			$shop["imgurl"]=images_site($shop["imgurl"]);
		}
		return $shop;
	}
	public function Dselect($option=array(),&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				 
				$data[$k]=$v;
			}
		}
		return $data;
	}
	 
	public function getListByIds($ids,$fields="shopid,title,imgurl,follow_num"){
		$option=array(
			"where"=>" shopid in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['shopid']]=$rs;
			}
			return $data;
		}
	}
	
	public function orderList(){
		return array(
			"zhpx"=>"综合排序",
			"quick"=>"速度最快",
			"raty_grade"=>"评分最高",
			"send_price"=>"起送价最低",
			"avg_price"=>"人均高到低"
		);
	}
}

?>