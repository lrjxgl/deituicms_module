<?php
class b2b_pushModel extends model{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function sendShop($option){
		$table=$option['table'];
		if(is_array($option['content'])){
			$content=$option['content']['content'];
			$con=$option['content'];
		}else{
			$content=$option['content'];
		}
		if(isset($option['title'])){
			$title=$option['title'];
		}else{
			$title="通知";
		}
		$shopid=$option['shopid'];
		if(isset($option['retable'])){
			$retable=$option['retable'];
		}
		
		$adminids=M("mod_b2b_admin")->selectCols(array(
			"where"=>" shopid=".$shopid,
			"fields"=>" adminid "
		));
		 
		if($adminids){
			//发送通知
			foreach($adminids as $adminid){
				M("mod_b2b_shop_notice")->insert(array(
						"dateline"=>time(),
						"content"=>$content, 
						"adminid"=>$adminid,
						"shopid"=>$shopid,
						"linkurl"=>arr2str($option['linkurl']),
						"title"=>$title
				));
			}
			/*
			$pushlist=M("apppush")->select(array(
				"where"=>"  ".$table."admin in("._implode($adminids).") "
			));
			if($pushlist){
				 
				foreach($pushlist as $v){
					$pdata=array(
						"pid"=>$v['id'],
						"typeid"=>1,
						"dateline"=>time(),
						"content"=>arr2str($option['content']),
						"template_id"=>$option['template_id'],
						"url"=>$option['url']
					);
					M("apppush_plan")->insert($pdata);
				}
			}
			*/
			
		}
		//End 推送信息
		
	}
}
?>