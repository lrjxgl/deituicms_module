<?php
class csc_cbdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$list=M("mod_csc_cbd")->select(array(
			"where"=>" status=1 "
		));
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		$seo=M("seo")->get("csc_cbd");
		$this->smarty->goAssign(array(
			"list"=>$list,
			"seo"=>$seo
		));
		$this->smarty->display("csc_cbd/index.html");
	}
	
	public function onShow(){
		$cbdid=get("cbdid","i");
		$cbd=M("mod_csc_cbd")->selectRow("cbdid=".$cbdid);
		if(!$cbd){
			$this->goAll("数据出错",1);
		}
		$cbd["imgurl"]=images_site($cbd["imgurl"]);
		$sql="select s.*,b.cbdid from ".table('mod_csc_cbd_shop')." as b 
			left join ".table('mod_csc_shop')." as s
			on b.shopid=s.shopid
			where b.cbdid=".$cbdid."
		" ;
		$data=MM("csc","csc_shop")->getAll($sql);
		if($data){
			foreach($data as $v){
				$shopids[]=$v["shopid"];
			}
			$pros=MM("csc","csc_product")->select(array(
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
				if($gps){
					$v['distance']=distanceByLnglat($lng,$lat,$v['lng'],$v['lat']);
					$v['distance']=m2km($v['distance']);
				}
				$data[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$data,
			"cbd"=>$cbd
		));
		$this->smarty->display("csc_cbd/show.html");
	}
	
	public function onCbdShop(){
		$cbdid=get("cbdid","i");
		$cbd=M("mod_csc_cbd")->selectRow("cbdid=".$cbdid);
		$sql="select s.*,b.cbdid from ".table('mod_csc_cbd_shop')." as b 
			left join ".table('mod_csc_shop')." as s
			on b.shopid=s.shopid
			where b.cbdid=".$cbdid."
		" ;
		$list=MM("csc","csc_shop")->getAll($sql);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"cbd"=>$cbd
		));
	}
}