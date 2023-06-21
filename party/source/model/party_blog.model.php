<?php
class party_blogModel extends model{
	public $table="mod_party_blog";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			foreach($res as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$v["user"]=$us[$v["userid"]];
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
			
		}
		return $res;
	}
}