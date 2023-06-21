<?php
class jdo2o_articleModel extends model{
	public $table="mod_jdo2o_article";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		if(!isset($option["fields"])){
			$option["fields"]="id,status,shopid,love_num,comment_num,view_num,title,imgurl,imgsdata,description";
		}
		$res=$this->select($option,$rscount);
		if($res){
			 
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				if($v['imgsdata']){
					$imgs=explode(",",$v['imgsdata']);
					$imgslist=array();
					foreach($imgs as $img){
						$imgslist[]=images_site($img);
					}
					$v['imgslist']=$imgslist;
				}
				$res[$k]=$v;
			}
			return $res;
		}
	}
}