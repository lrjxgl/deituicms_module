<?php
class jdo2o_blogControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=24;
		$type=get("type","h");
		switch($type){
			case "recommend":
				$where=" isrecommend=1 ";
				
				break;
			default:
				
				$where=" 1 ";
				break;
		}
		$ops=array(
			"where"=>$where,
			"order"=>" id DESC",
			"start"=>$start,
			"limit"=>$limit	
		);
		$rscount=true;
		$list=MM("jdo2o","jdo2o_blog")->Dselect($ops,$rscount);
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"list"=>$list,
			"per_page"=>$per_page,		
			"rscount"=>$rscount,
			"pagelist"=>$pagelist,
		));
		$this->smarty->display("jdo2o_blog/index.html");
	}
	
	public function onDelete(){
 
		$id=get("id","i");
		 
		M("mod_jdo2o_blog")->update(array("status"=>11,"isrecommend"=>0),"id=".$id);
		$this->goAll("删除成功");
	}
	public function onStatus(){
		$id=get_post('id',"i");
		$row=M("mod_jdo2o_blog")->selectRow("id=".$id);
		$status=1;
		if($row["status"]==1){
			$status=2;
		}
		M("mod_jdo2o_blog")->update(array(
			"status"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
	public function onRecommend(){
		$id=get_post('id',"i");
		$row=M("mod_jdo2o_blog")->selectRow("id=".$id);
		$status=1;
		if($row["isrecommend"]==1){
			$status=2;
		}
		M("mod_jdo2o_blog")->update(array(
			"isrecommend"=>$status
		),"id=".$id);
		$this->goAll("success",0,$status);
	}
}