<?php
class job_searchControl extends skymvc{
	public function onDefault(){
		$keyword=get("keyword","h");
		$start=get("per_page","i");
		$limit=12;
		$rscount=true;
		$where=" title like '%".$keyword."%' AND status=1 ";
		$tab=get("tab","h");
		$table=$tab=='jianzhi'?'job_jianzhi':'job_quanzhi';
		$list=[];
		if($keyword){
			$list=MM("job",$table)->select(array(
				"where"=>$where,
				"order"=>"id DESC",
				"start"=>$start,
				"limit"=>$limit
			),$rscount);
		}
		
		$per_page=$per_page+$limit;
		$per_page=$per_page>$rscount?0:$per_page; 
			
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"per_page"=>$per_page,
			"keyword"=>$keyword
		));
		$this->smarty->display("job_search/index.html");
		
	}
}
