<?php
class house_resourceModel extends model{
	public $table="mod_house_resource";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids){
			$ids=array_unique($ids);
			$res=$this->Dselect(array(
				"where"=>" id in("._implode($ids).") "
			));
			if($res){
				foreach($res as $rs){
					$list[$rs["id"]]=$rs;
				}
				return $list;
			}
		}
	public function Dselect($option=array(),&$rscount=false){
		$data=$this->select($option,$rscount);
		 
		if($data){
			$uids=[];
			$scids=[];
			foreach($data as $v){
				$uids[]=$v['userid'];
				$scids[]=$v["sc_id"]; 
			}
			 
			$us=M("user")->getUserByIds($uids); 
			$scs=M("site_city")->getListByIds($scids);
			foreach($data as $k=>$v){
				if(isset($scs[$v["sc_id"]])){
					$v["sc_id_title"]=$scs[$v["sc_id"]];
				}else{
					$v["sc_id_title"]="";
				}
				$v['nickname']=$us[$v['userid']]['nickname'];
				$v['user_head']=images_site($us[$v['userid']]['user_head']);
				if($v['imgsdata']){
					$imgs=explode(",",$v['imgsdata']);
					$imgslist=array();
					foreach($imgs as $img){
						$imgslist[]=images_site($img);
					}
					$v['imgslist']=$imgslist;
				}
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
}