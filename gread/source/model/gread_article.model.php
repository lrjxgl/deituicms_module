<?php
class gread_articleModel extends model{
	public $table="mod_gread_article";
	
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	public function Dselect($ops,&$rscount=false){
		if(!isset($ops["fields"])){
			$ops["fields"]="id,title,status,imgurl,description";
		}
		$res=$this->select($ops,$rscount);
		if($res){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$res[$k]=$v;
			}
			return $res;
		}
	}
	public function getList(){
		
	}
	 
	
}
?>