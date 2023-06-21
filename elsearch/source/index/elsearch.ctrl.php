<?php
class elsearchControl extends skymvc{
	
	public function onDefault(){
		$tableList=M("mod_elsearch_table")->select(array(
			"where"=>" status=1 "
		));
		$keyword=get("keyword","h");
		$this->smarty->goAssign(array(
			"tableList"=>$tableList,
			"keyword"=>$keyword
		));
		$this->smarty->display("elsearch/index.html");
	}
	
	public function onSearch(){
		 
		//require 'extends/pscws23/pscws3.class.php';
		$keyword = get("keyword","h");
		/*$gword=[];
		if($keyword){
			$cws = new PSCWS3('extends/pscws23/dict/dict.xdb');
			$words = $cws->segment(mb_convert_encoding($keyword,"gbk","utf-8"));
			
			foreach($words as $k=>$w){
				if(strlen($w)<3){
					unset($words[$k]);
				}else{
					$gword[]=mb_convert_encoding($w,"utf-8","gbk");
				}
			}
			$keyword=implode(" ",$words);
			$keyword=mb_convert_encoding($keyword,"utf-8","gbk");
		}*/
		/*
		$titleLikes=" ( ";
		$contentLikes=" ( ";
		foreach($gword as $k=>$w){
			if($k>0){
				$titleLikes.=" or ";
				$contentLikes.=" AND ";
			}
			$titleLikes.=" title like '%".$w."%' ";
			$contentLikes.=" content like '%".$w."%' ";
		}
		$titleLikes.=" ) ";
		$contentLikes.=" ) ";
		$tablename=get("tablename","h");
		$where=" 1 ";
		if($tablename){
			$where=" tablename='".$tablename."' ";
		}*/
		$list=[];
		$rscount=0;
		if(!empty($keyword)){
			//$where.=" AND ".$contentLikes." ";
			$tablename=get("tablename","h");
			$where=" 1 ";
			if($tablename){
				$where=" tablename='".$tablename."' ";
			}
			$where.=" AND MATCH (content) AGAINST ('".$keyword."' IN NATURAL LANGUAGE MODE) ";
			//$sql="select * from ".table("mod_elsearch_topic")."  WHERE ".$where."  limit 12 ";
			
			$sql="
				select id,title,description,tablename,objectid,
				MATCH (content) AGAINST ('".$keyword."') as ograde 
				from ".table("mod_elsearch_topic")."  WHERE ".$where." order by ograde DESC limit 10  ";
			 
			$list=M("mod_elsearch_topic")->getAll($sql);
			$rscount=M("mod_elsearch_topic")->selectOne(array(
				"where"=>$where,
				"fields"=>"count(*) as ct "
			));
		}
		
		$this->goAll("success",0,array(
			"list"=>$list,
			"rscount"=>$rscount
		));
	}
	
	
	 
 
	 
	 
	 
	 
	
}
?>