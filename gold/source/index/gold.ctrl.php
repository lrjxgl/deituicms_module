<?php
class goldControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}

	public function onDefault(){
		$where=" status=1 ";
		$url="/module.php?m=gold_product&a=default";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_gold_product")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$data[$k]=$v;
			}
		}
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"per_page"=>$per_page,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url
			)
		);
		$this->smarty->display("gold/index.html");
	}
}

?>