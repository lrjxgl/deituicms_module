<?php
class house_searchControl extends skymvc{
	public function onDefault(){
		$keyword=get("keyword","h");
		$this->smarty->goAssign(array(
			"keyword"=>$keyword
		));
		$this->smarty->display("house_search/index.html");
	}
	public function onResource(){
		$where=" status=1 ";
		$url="/module.php?m=house_resource&a=default";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND description like '%".$keyword."%' ";
		}
		 
		$limit=20;
		$start=get("per_page","i");
		$type=get("type","h");
		if($type){
			$url.="&type=".$type;
			switch($type){
				case "new":
					$where.=" AND isnew=1 ";
					break;
				case "ershou":
					$where.=" AND isnew=0 ";
					break;
			}
		}
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=MM("house","house_resource")->Dselect($option,$rscount);
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
	public function onLoupan(){
		$where=" status=1 ";
		$url="/module.php?m=house_loupan";
		$keyword=get("keyword","h");
		if($keyword){
			$where.=" AND title like '%".$keyword."%' ";
		}
		$limit=20;
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("mod_house_loupan")->select($option,$rscount);
		if($data){
			foreach($data as &$v){
				$v['imgurl']=images_site($v['imgurl']);
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goassign(
			array(
				"list"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"per_page"=>$per_page,
			)
		);
	}
}