<?php
class tutor_shopModel extends model{
	public $table="mod_tutor_shop";
	 
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if($res){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$res[$k]=$v;
			}
		}
		return $res;
	}
	public function get($shopid,$fields="*"){
		$book=$this->selectRow(array(
			"where"=>" shopid=".$shopid,
			"fields"=>$fields
		));
		if($book){
			$book["imgurl"]=images_site($book["imgurl"]);
		}
		return $book;
	}
	public function getListByIds($shopids,$fields="*"){
		$data=$this->select(array(
			"where"=>"shopid in("._implode($shopids).") ",
			"fields"=>$fields
		));
		if($data){
			foreach($data as $v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$ndata[$v['shopid']]=$v;				
			}
			return $ndata;
		}
		
	}
	 
	
}
?>