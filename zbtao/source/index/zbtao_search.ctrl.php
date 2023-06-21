<?php
class zbtao_searchControl extends skymvc{
	public function onDefault(){
		$keyword=get("keyword","h");
		$this->smarty->goAssign(array(
			"keyword"=>$keyword
		));
		$this->smarty->display("zbtao_search/index.html");
	}
	
	public function onProduct(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		$where.=" AND title like '%".$keyword."%' ";
		$url="/moduleadmin.php?m=zbtao_live_product&a=default";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" productid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("zbtao","zbtao_live_product")->Dselect($option,$rscount);
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
		 
	}
	
	public function onPP(){
		$where=" status=1 ";
		$keyword=get("keyword","h");
		$where.=" AND nickname like '%".$keyword."%' ";
		$url="/moduleadmin.php?m=zbtao_pp&a=default";
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" ppid DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("zbtao","zbtao_pp")->Dselect($option,$rscount);
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
		 
	}
	
}