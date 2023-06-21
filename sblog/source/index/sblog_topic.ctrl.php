<?php
class sblog_topicControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$where="status=1";
		$type=get("type","h");
		switch($type){
			case "all":
				break;
			default:
				$where.=" AND isrecommend=1 ";
				break;
		}
		$start=get("per_page","i");
		$limit=48;
		$rscount=true;
		$topicList=M("mod_sblog_topic")->select(array(
			"where"=>$where,
			"limit"=>$limit,
			"start"=>$start
		),$rscount);
		$per_page=$start+$limit;
		$per_page=$per_page<$rscount?$per_page:0;
		$this->smarty->goAssign(array(
			"topicList"=>$topicList,
			"per_page"=>$per_page
		));
		$this->smarty->display("sblog_topic/index.html");
	}
	
	public function onShow(){
		$id=get("id","i");
		$topic=M("mod_sblog_topic")->selectRow("id=".$id);
		if(empty($topic)){
			$this->goAll("话题下线了",1);
		}
		$topic["imgurl"]=images_site($topic["imgurl"]);
		$this->smarty->goAssign(array(
			"topic"=>$topic
		));
		$this->smarty->display("sblog_topic/show.html");
	}
	
}
?>