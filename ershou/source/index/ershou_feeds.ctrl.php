<?php
class ershou_feedsControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("ershou_feeds/index.html");
	}
	
	public function onList(){
		$userid=M("login")->userid;
		$list=M("feeds")->select(array(
			"where"=>" tablename in('mod_ershou_product','mod_group_title') AND userid=".$userid
		));
		if(!empty($list)){
			$gg=[];
			$pp=[];
			$ggs=$pps=[];
			foreach($list as $v){
				if($v["tablename"]=='mod_group_title'){
					$gg[]=$v["objectid"];
				}elseif($v["tablename"]=='mod_ershou_product'){
					$pp[]=$v["objectid"];
				}
			}
			if(!empty($gg)){
				$ggs=MM("group","group_title")->getListByIds($gg);
			}
			if(!empty($pp)){
				$pps=MM("ershou","ershou_product")->getListByIds($pp);
			}
			foreach($list as $k=>$v){
				if($v["tablename"]=='mod_ershou_product'){
					$v=array_merge($v,$pps[$v["objectid"]]);
					$v["cmList"]=MM("ershou","ershou_product_comment")->getListByTopicId($v["objectid"],2);
				}else{
					$v=array_merge($v,$ggs[$v["objectid"]]);
					$v["cmList"]=MM("group","group_comment")->getListByTopicId($v["objectid"],2);
				}
				$list[$k]=$v;
			}
		}
		$this->goAll("success",0,array(
			"list"=>$list
		));
	}
}