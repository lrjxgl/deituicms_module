<?php
class freeshop_feedsControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=6;
		$type=get("type","h");
		$order=" id DESC";
		$userid=M("login")->userid;
		$rscount=true;
		$blogids=M("mod_freeshop_feeds")->selectCols(array(
			"where"=>" userid=".$userid,
			"fields"=>"productid",
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit
		),$rscount);
		if(!$blogids){
			$blogids=[0]; 
		} 
		$ops=array(
			"where"=>" status=1 AND  productid in("._implode($blogids).") ",
			"order"=>"  productid DESC"
		);
		
		$list=MM("freeshop","freeshop_product")->Dselect($ops);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
			 
		));
		$this->smarty->display("freeshop_feeds/index.html");
	}
	
}