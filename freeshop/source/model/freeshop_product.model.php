<?php
class freeshop_productModel extends model{
	public $table="mod_freeshop_product";
	public function __construct(){
		parent::__construct();
	}
	
	public function catList(){
		$res=M("mod_freeshop_category")->select(array(
			"where"=>" status=1 ",
			"order"=>"orderindex ASC"
		));
		if($res){
			$list=array();
			foreach($res as $v){
				$list[$v["catid"]]=$v;
			}
			return $list;
		}
		return array(
			1=>array(
				"catid"=>1,
				"title"=>"美食"
			),
			2=>array(
				"catid"=>2,
				"title"=>"休闲玩乐"
			),
			3=>array(
				"catid"=>3,
				"title"=>"美容美发"
			),
			4=>array(
				"catid"=>4,
				"title"=>"学习培训"
			),
			5=>array(
				"catid"=>5,
				"title"=>"家居"
			),
			11=>array(
				"catid"=>11,
				"title"=>"其他"
			)
		);
	}
	
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			 
			foreach($res as $v){
				$uids[]=$v["userid"];
				 
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			$catList=$this->catList(); 
			foreach($res as $k=>$v){
				$v["user"]=$us[$v["userid"]];
				$v["catid_name"]=$catList[$v["catid"]]["title"];
				if($v['imgsdata']){
					$imgs=explode(",",$v['imgsdata']);
					$imgslist=array();
					foreach($imgs as $img){
						$imgslist[]=images_site($img);
					}
					$v['imgslist']=$imgslist;
					$v["imgurl"]=images_site($v["imgurl"]);
				}
				$v["status_name"]=$v["etime"]>time()?'抢购中':'已结束'; 
				$v["etime_date"]=date("Y-m-d H:i:s",$v["etime"]);
				$v["parsecontent"]=$this->parseContent($v["content"]);
				$res[$k]=$v;
			}
			
		}
		return $res;
	}
	
	public function parseContent($content){
		preg_match_all("/#(.*)#/iUs",$content,$tps);
		 
		if(isset($tps[1])){
			
			foreach($tps[1] as $tp){
				$content=str_replace("#{$tp}#",'<a class="blogLink" href="/module.php?m=freeshop_product&a=topic&title='.urlencode(html($tp)).'">#'.html($tp).'#</a>',$content);
			}
		}
		return $content;
	}
	
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$res=$this->select(array("where"=>"productid in("._implode($ids).")"));
		$data=[];
		if(!empty($res)){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['productid']]=$rs;
			}
			
		}
		return $data;
	}
}