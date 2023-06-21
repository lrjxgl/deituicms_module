<?php
class taoke_favControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
		$userid=M("login")->userid;
		
		$where="userid=".$userid;
		$url="/index.php?m=fav&a=my";
		$limit=20;
		$tablename=get("tablename","h");
		$tablename=$tablename?$tablename:"taoke";
		$where.=" AND tablename='".$tablename."' ";
		$url.="&tablename=".urlencode($tablename);
		$start=get("per_page","i");
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
			"where"=>$where
		);
		$rscount=true;
		$data=M("fav")->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$ids[]=$v['object_id'];
			}
			switch($tablename){
				case "article":
					$arts=M("article")->id_list(array(
						"where"=>" id in("._implode($ids).")"
					));
					$url="/index.php?m=article&a=show&id=";
					break;
				case "forum":
					$arts=M("forum")->getListByIds($ids);
					$url="/index.php?m=forum&a=show&id=";
					break;
				case "taoke":
					$arts=MM("taoke","taoke")->getListByIds($ids);
					$url="/index.php?m=taoke&a=show&id=";
					break;
			}
			foreach($data as $k=>$v){
				$v=$arts[$v['object_id']];
				$v['url']=$url.$v['id'];
				$data[$k]=$v;
			}
		}
	 
		$pagelist=$this->pagelist($rscount,$limit,$url);
		//end分页
		$per_page=$start+$limit;
		$per_page=$per_page>=$rscount?0:$per_page;
		$this->smarty->goassign(
			array(
				"data"=>$data,
				"pagelist"=>$pagelist,
				"rscount"=>$rscount,
				"url"=>$url,
				"per_page"=>$per_page
			)
		);
		
		$this->smarty->display("taoke_fav/index.html");
	}
	
}
?>