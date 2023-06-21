<?php
class exue_blogModel extends model{
	public $table="mod_exue_blog";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			foreach($res as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			foreach($res as $k=>$v){
				$v["user"]=$us[$v["userid"]];
				if($v['imgsdata']){
					$imgs=explode(",",$v['imgsdata']);
					$imgslist=array();
					foreach($imgs as $img){
						$imgslist[]=images_site($img);
					}
					$v['imgslist']=$imgslist;
					$v["imgurl"]=images_site($v["imgurl"]);
				}
				$v["parsecontent"]=$this->parseContent($v["content"]);
				$res[$k]=$v;
			}
			return $res;
		}
	}
	
	public function parseContent($content){
		preg_match_all("/#(.*)#/iUs",$content,$tps);
		 
		if(isset($tps[1])){
			
			foreach($tps[1] as $tp){
				$content=str_replace("#{$tp}#",'<a class="blogLink" href="/module.php?m=exue_blog&a=topic&title='.urlencode(html($tp)).'">#'.html($tp).'#</a>',$content);
			}
		}
		return $content;
	}
}