<?php
class aichat_token_logControl extends skymvc{
	
	public function __construct(){
		parent::__construct();	
	}
	
	public function onInit(){
		M("login")->checkLogin();
	}
	
	public function onDefault(){
		$where="  userid=".M("login")->userid;
		$start=get('per_page','i');
		$limit=20;
		$url="/module.php?m=aichat_token_log";
		$option=array(
			"where"=>$where,
			"start"=>$start,
			"limit"=>$limit,
			"order"=>" id DESC",
		);
		$rscount=true;
		$data=M("mod_aichat_token_log")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['timeago']=timeago(strtotime($v['createtime']));
				
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$per_page=$start+$limit;
		$per_page=$rscount>$per_page?$per_page:0;
		$this->smarty->goassign(array(
			"list"=>$data,
			"per_page"=>$per_page,
			"rscount"=>$rscount,
			"pagelist"=>$pagelist
			
		));
		
		$this->smarty->display("aichat_token_log/index.html");
	}
	
}
?>