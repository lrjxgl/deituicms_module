<?php
class olprint_shoplistControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where=" status=1 ";
		
		$order=" shopid DESC ";
		$fields=" shopid,shopname,lat,lng,address,imgurl,raty_grade ";
		$gps=gps_get();
		 
		$lat=$gps['lat'];
		$lng=$gps['lng'];
	 
		$start=get("per_page","i");
		$limit=1000; 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"fields"=>$fields,
			"where"=>$where
		);
		$rscount=true;
		$shopList=MM("olprint","olprint_shop")->Dselect($option,$rscount);
		$distance=$shopids=array();
		
		if($lat>0 && $lng>0){
			 
			foreach($shopList as $k=>$v){
				$distance[$k]=$v['distance']=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
				$shopids[$k]=$v['shopid'];
				if($v['distance']<1000){
					$v['distance']=$v['distance']."m";
				}else{
					$v['distance']=round($v['distance']/1000,2)."km";
				}
			 
				$shopList[$k]=$v;
			}
			array_multisort ( $distance ,  SORT_ASC ,  $shopids ,  SORT_ASC ,  $shopList );
		}
	 
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		 
		$this->smarty->goAssign(array(
			"list"=>$shopList,
			"per_page"=>$per_page,
			
		));
		$this->smarty->display("olprint_shoplist/index.html");
	}
	
	 
}