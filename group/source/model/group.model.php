<?php
class groupModel extends model{
	public $table="mod_group";	
	public function __construct(){
		parent::__construct();
	}
	
	public function select($option=array(),&$rscount=false,$cache = 0, $expire = 60){
		$data=parent::select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['glogo']=images_site($v['glogo']);
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
	public function getListByIds($ids){
		$rss=$this->select(array(
			"where"=>"gid in ("._implode($ids).")"
		));
		if($rss){
			foreach($rss as $rs){
				$data[$rs['gid']]=$rs;
			}
			return $data;
		}
	}
	public function get($gid){
		$gid=intval($gid);
		$data=$this->selectRow("gid=".$gid);
		
		$data['isjoin']=0;
		$data['isadmin']=0;
		$data['isfound']=0;
		$data['isforbid']=0;
		$data['glogo']=images_site($data['glogo']);
		$userid=M("login")->userid;
		if($userid){
			$gu=M("mod_group_user")->selectRow("userid=".$userid." AND gid=".$gid);
			if($gu){
				$data['isjoin']=1;
				if($gu['isadmin']){
					$data['isadmin']=1;
				}
				if($gu['isfound']){
					$data['isfound']=1;
				}
				if($gu['status']!=2){
					$data['isforbid']=1;
				}
			}
			
		}
		
		return $data;
		
	}
	
}
?>