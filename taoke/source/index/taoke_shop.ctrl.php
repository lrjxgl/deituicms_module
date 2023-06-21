<?php
class taoke_shopControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$where=" status=2 ";
		$url="/module.php?m=taoke_shop";
		$catid=get('catid','i');
		 
		$cat=M("mod_taoke_shop_type")->selectRow("catid=".$catid);
		if($catid){
			$where.=" AND catid=".$catid;
			$url.="&catid=".$catid;
		}
		 
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" shopid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_taoke_shop")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				$data[$k]=$v;
			}
		}
		$typelist=MM("taoke","taoke_shop_type")->getList(2);
		if($catid){
			foreach($typelist as $k=>$v){
				if($v['catid']==$catid){
					$v['isactive']="active";
				}else{
					$v['isactive']="";
				}
				
				
				$typelist[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
			
		 
		$this->smarty->goassign(
			array(
				 
			 
				"cat"=>$cat,
				"data"=>$data,
				"typelist"=>$typelist,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"per_page"=>$per_page,
			)
		);
		 
		$this->smarty->display("taoke_shop/index.html");
	}
	
}
