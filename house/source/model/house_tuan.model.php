<?php
class house_tuanModel extends model{
	
	public $table="mod_house_tuan";
	public function __construct(){
		parent::__construct();
	}
	public function getStatus($stime,$etime){
		$stime=strtotime($stime);
		$etime=strtotime($etime);
		$time=time();
		if($stime>$time){
			return "即将开始";
		}elseif($etime<$time){
			return "已结束";
		}else{
			return "正在进行";
		}
	}
	public function Dselect($option,&$rscount=false){
		$data=M("mod_house_tuan")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$v["status_name"]=$this->getStatus($v["stime"],$v["etime"]);
				$data[$k]=$v;
			}
		}
		return $data;
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->Dselect(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			foreach($res as $rs){
				$list[$rs["id"]]=$rs;
			}
			return $list;
		}
	}
	
}