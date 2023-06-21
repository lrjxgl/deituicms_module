<?php
class gread_bookModel extends model{
	public $table="mod_gread_book";
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function Dselect($ops,&$rscount=false){
		if(!isset($ops["fields"])){
			$ops["fields"]="bookid,catid,title,status,imgurl,price,description";
		}
		$list=$this->select($ops,$rscount);
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		return $list;
	}
	
	public function get($bookid,$fields="*"){
		$book=$this->selectRow(array(
			"where"=>" bookid=".$bookid,
			"fields"=>$fields
		));
		if($book){
			$book["imgurl"]=images_site($book["imgurl"]);
		}
		return $book;
	}
	public function getListByIds($ids,$fields="*"){
		$option['where']=" bookid in("._implode($ids).")";
		$option["fields"]=$fields;
		$data=$this->select($option);
		$rdata=array();
		if($data){
			foreach($data as $v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$rdata[$v['bookid']]=$v;
			}
		}
		return $rdata;
	}
	
}
?>