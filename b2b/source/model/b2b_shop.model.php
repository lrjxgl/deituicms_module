<?php
class b2b_shopModel extends model{
	public $table="mod_b2b_shop";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid,$fields="*"){
		$shop=$this->selectRow(array(
			"where"=>" shopid=".$shopid,
			"fields"=>$fields
		));
		if(isset($shop["imgurl"])){
			$shop["imgurl"]=images_site($shop["imgurl"]);
		}
		return $shop;
	}
	public function Dselect($option=array(),&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$ims=explode(",",$v['imgsdata']);
				$imgsdata=array();
				foreach($ims as $im){
					if($im!=''){
						$imgsdata[]=images_site($im);
					}
					
				}
				$v['imgsdata']=$imgsdata;
				$data[$k]=$v;
			}
		}
		return $data;
	}
	/**
	 * 显示橱窗
	 */
	public function DselectWindow($option=array(),&$rscount=false){
		$gps=gps_get();
		 
		$data=$this->select($option,$rscount);
		if($data){
			
			foreach($data as $v){
				$shopids[]=$v["shopid"];
			}
			//橱窗产品
			$pros=MM("b2b","b2b_product")->select(array(
				"where"=>" iswindow=1 AND status=1 AND shopid in("._implode($shopids).")",
				"fields"=>" id,title,imgurl,shopid,month_buy_num,price"
			));
			
			$sps=array();
			if($pros){
				foreach($pros as $p){
					$p['imgurl']=images_site($p['imgurl']);
					$sps[$p['shopid']][]=$p;
				}
			}
			//优惠券
			$cps=MM("b2b","b2b_coupon")->select(array(
				"where"=>" status=1  AND shopid in("._implode($shopids).")",
				"fields"=>"id,title,shopid"
			));
			$scps=array();
			if($cps){
				foreach($cps as $p){
					$scps[$p["shopid"]][]=$p;
				}
			}
			foreach($data as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$ims=explode(",",$v['imgsdata']);
				$imgsdata=array();
				foreach($ims as $im){
					if($im!=''){
						$imgsdata[]=images_site($im);
					}
					
				}
				$v['imgsdata']=$imgsdata;
				$v['prolist']=$sps[$v['shopid']];
				$v["coupons"]=$scps[$v["shopid"]];
				if($gps){
				 
					$v['distance']=distanceByLnglat($gps["lng"],$gps["lat"],$v['lng'],$v['lat']);
					 
					if($v["distance"]<1000){
						$v["distance"].="m";
					}else{
						$v["distance"]=round($v["distance"]/1000,2)."Km";
					}
					//$v['distance']=m2km($v['distance']);
				}
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
	public function getListByIds($ids,$fields="shopid,shopname,imgurl"){
		$option=array(
			"where"=>" shopid in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['shopid']]=$rs;
			}
			return $data;
		}
	}
	
	public function orderList(){
		return array(
			"zhpx"=>"综合排序",
			"quick"=>"速度最快",
			"raty_grade"=>"评分最高",
			"send_price"=>"起送价最低",
			"avg_price"=>"人均高到低"
		);
	}
	/*vip商家*/
	public function vipList($limit=5){
		$res=M("mod_b2b_shop_hotvip")->select(array(
			"where"=>"isfinish=0",
			"order"=>"spid DESC",
			"fields"=>"shopid,spid",
			"limit"=>100
		));
		$shopids=[];
		$spids=[];
		if(!empty($res)){
			foreach($res as $rs){
				if(!in_array($rs["shopid"],$shopids)){
					$shopids[]=$rs["shopid"];
					$spids[$rs["shopid"]]=$rs["spid"];
				}
				
			}
		}
		
		$list=[];
		 
		if(!empty($shopids)){
			shuffle($shopids); 
			$shopids=array_slice($shopids,0,$limit);
			$list=MM("b2b","b2b_shop")->DselectWindow(array(
				"where"=>" shopid in("._implode($shopids).") "
			));
			if(!empty($list)){
				foreach($list as $k=>$v){
					$v["spid"]=$spids[$v["shopid"]];
					$list[$k]=$v;
				}
			}
		}
		return $list;
	}
}

?>