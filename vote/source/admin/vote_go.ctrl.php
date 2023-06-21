<?php
class vote_goControl extends skymvc{
	public $shop_app;
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		 
		$vid=get_post("vid","i");
		$vote=M("mod_vote")->selectRow("id=".$vid);
		$this->smarty->assign(array(
			"vote"=>$vote
		));
	}
	
	public function onDefault(){
		$vid=get('vid','i');
		$url="/moduleadmin.php?m=vote_go&vid=".$vid;	
		
		$start=get('per_page','i');
		$limit=24;
		$where=" 1 ";
		$vid && $where="  vid=".$vid." ";
		$order="id DESC";
		 
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>$order,
			"where"=>$where
		);
		$rscount=true;
		$data=MM("vote","mod_vote_go")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$uids[]=$v['userid'];
				$jids[]=$v['joinid']; 	
			}
			
			$us=M("user")->getUserByIds($uids);
			$joins=MM("vote","mod_vote_join")->getListByIds($jids);
			foreach($data as $k=>$v){
				$v['user']=$us[$v['userid']];
				$v['join']=$joins[$v['joinid']];
				$data[$k]=$v;
			}
			
		}
		 
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->assign(array(
			"pagelist"=>$pagelist,
			"rscount"=>$rscount,
			"data"=>$data,
		 
		));
		if(!$vid){
			$this->smarty->display("vote_go/indexall.html");
		}else{
			$this->smarty->display("vote_go/index.html");
		}
	}
	
	 
	
}

?>