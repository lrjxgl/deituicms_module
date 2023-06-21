<?php
class recycle_shopControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("recycle_shop/index.html");
	}
	
	public function onNear(){
		$list=MM("recycle","recycle_shop")->select(array(
			"where"=>" status=1 "
		));
		$this->smarty->display("recycle_shop/near.html");
	}
	
	public function onNearData(){
		$userid=M("login")->userid;
		$lat=get('lat','h');
		$lng=get('lng','h');
		$_SESSION['latlng']=array(
			"lat"=>$lat,
			"lng"=>$lng
		);
		
		$meter=0.00001*1.1;//1米以内
		$meter=$meter*50000000;
		$ilng=$lng+$meter;
		$mlng=$lng-$meter;
		$ilat=$lat+$meter;
		$mlat=$lat-$meter;
		
		$where=" status=1 ";
		if($lat>0 && $lng>0){
			$where.=" AND (lng<'$ilng' AND  lng>'$mlng' AND  lat>'$mlat' AND  lat<'$ilat')  ";
		}
		$rscount=true;
		$arr=M("mod_recycle_shop")->select(array(
			"where"=>$where
		),$rscount);
		$start=get('per_page','i');
		$limit=24;
		$data=array();
		if($arr){ 
			foreach($arr as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$distance[$k]=$v['distance']=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
				$arr[$k]=$v;					 					
			}				  
			array_multisort ( $distance ,  SORT_ASC ,  $arr );
			$rscount=count($arr);
			$end=$start+$limit;
			$end=$end>$rscount?$rscount:$end;
			
			for($i=$start;$i<$end;$i++){
				$data[]=$arr[$i];
				$uids[]=$arr[$i]['userid'];
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data
		));
	}
	
	public function onSetshop(){
		$shopid=get("shopid","i");
		setcookie("recycle_shopid",$shopid,time()+3600*24*365);
		$data["recycle_shopid"]=$shopid;
		 
		$this->goAll("success",0,$data);
	}
	
}