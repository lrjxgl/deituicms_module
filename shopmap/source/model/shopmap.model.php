<?php
class shopmapModel extends model{
	public $table="mod_shopmap";
	public function __construct(){
		parent::__construct();
	}
	
	public function Dselect($option,&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			$gps=gps_get();
			$lat=$gps['lat'];
			$lng=$gps['lng'];
			foreach($data as $k=>$v){
				$v['distance']=0;
				if($gps){
					
					$v['distance']=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
					$v['distance']=m2km($v['distance']);
				}
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
}

?>