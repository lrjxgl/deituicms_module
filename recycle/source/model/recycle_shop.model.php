<?php
class recycle_shopModel extends model{
	public $table="mod_recycle_shop";
	public function __construct(&$base=null){
		parent::__construct($base);
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
	public function getListByIds($shopids){
		$data=$this->select(array("where"=>"shopid in("._implode($shopids).") "));
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