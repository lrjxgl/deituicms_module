<?php
class mod_cj1Model extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_cj1";
	}
	
	public function getListByIds($ids){
		$res=parent::select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>"id,title,imgurl"
		));
		
		$data=array();
		if($res){
			foreach($res as $rs){
				$data[$rs['id']]=$rs;
			}
			 
			return $data;
		}
		
	}
	
	public function select($option=array(),&$rscount=false,$cache = 0, $expire = 60){
		$data=parent::select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['starttime_fmt']=date("Y-m-d H:i",$v['dateline']);
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
			return $data;
		}
		
	}
}

?>