<?php
class bookModel extends model{
	public $table="mod_book";
	public function __construct(&$base=null){
		parent::__construct($base);
		$this->base=$base;
	}
	public function Dselect($option,&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
			return $data;
		}
		
	}
	public function getListByIds($ids){
		$rss=$this->select(array(
			"where"=>" bookid in("._implode($ids).") ",
			"fields"=>"bookid,title,imgurl,description"
		));
		if($rss){
			$data=array();
			foreach($rss as $v){
				$data[$v['bookid']]=$v;
			}
			return $data;
		}
	}
	
}

?>