<?php
class tutor_shoplistControl extends skymvc{
	public function onDefault(){
		$start=get("per_page","i");
		$limit=12;
		$where=" status=1 ";
		$catid=get("catid","i");
		if($catid){
			$cids=MM("tutor","tutor_category")->id_family($catid);
			$where.=" AND catid in("._implode($cids).") ";
		}
		$orderby=get("orderby","h");
		switch($orderby){
			case "raty_grade":
				$order=" raty_grade DESC ";
				break;
			case "sold_num":
				$order=" sold_num DESC ";
				break;
			default:
				$order=" grade DESC ";
				break;
		}
		$order="grade DESC";
		$rscount=true;
		$ops=array(
			"where"=>$where,
			"order"=>$order,
			"start"=>$start,
			"limit"=>$limit
		);
		$list=MM("tutor","tutor_shop")->Dselect($ops,$rscount);
		$catList=MM("tutor","tutor_category")->select(array(
			"where"=>" status=1 ",
			"order"=>"orderindex ASC"
		));
		$per_page=$start+$limit;
		$per_page=$per_page>$rscount?0:$per_page;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"catList"=>$catList,
			"per_page"=>$per_page,
			"catid"=>$catid
		));
		$this->smarty->display("tutor_shoplist/index.html");
	}
}