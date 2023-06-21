<?php
class taoke_searchcacheControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$etime=date("Y-m-d",time()-3600*48);
		$k='taobao';
		$where="sold_num>10 AND k='".$k."' AND etime>'".$etime."' ";
		$order=" view_num DESC";
		$limit=20;
		$start=get("per_page", "i");
		$type=get("type");
		switch($type){
			case "9.9":
				$where.=" AND price=9.9 ";
				break;
			case "gaofan":
				$where.=" AND  yj_bl>800 AND yj_money>5  ";
				break;	
			case "recommend":			
				$where.=" AND yj_money>10 AND yj_bl>800 ";
				break;
			case "new":
				$where.=" AND yj_money>10 AND yj_bl>800 ";
				$order="id DESC";
				break;
		}
		$orderby=get("orderby","h");
		switch($orderby){
			case "sold_num":
				$order=" sold_num DESC";
				break;
			case "priceAsc":
				$order="price ASC";
				break;
			case "maxBack":
				$order="yj_bl DESC";
				break;
		}
		$option=array(
		    "start"=>$start,
		    "limit"=>$limit,
		    "order"=>$order,
		    "where"=>$where
		);
		$rscount=true;
		$list=M("mod_taoke_searchcache")->select($option, $rscount);
		if($list){
			foreach($list as $k=>$v){
				$v=str2arr($v["content"]);
				$list[$k]=$v;
			}
		}
		 
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->goAll("success",0,array(
			"list"=>$list,
			"per_page"=>$per_page,
		));
	}
	
	public function onlistbyids(){
		$where="1";
		$idstr=get("ids");
		if (!empty($idstr)) {
		    $idss=explode(",", $idstr);
		    $ids=array();
		    foreach ($idss as $v) {
		        if (intval($v)>0) {
		            $ids[]=intval($v);
		        }
		    }
		    $where.=" AND objectid in("._implode($ids).") ";
		}
		$option=array(
		    "where"=>$where
		);
		$rscount=true;
		$list=M("mod_taoke_searchcache")->select($option, $rscount);
		if($list){
			foreach($list as $k=>$v){
				$v=str2arr($v["content"]);
				$list[$k]=$v;
			}
		}
		$this->goAll("success",0,array(
			"list"=>$list
		));
	} 
	
}
?>