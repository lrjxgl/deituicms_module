<?php
class voteControl extends skymvc{
	public $shop_app;
	public function __construct(){
		parent::__construct();
		 
	}
	
	public function onInit(){
		 $seo=array(
			"title"=>"微投票"
		); 
	 	$this->smarty->assign(array(
	 		"seo"=>$seo
	 	));
	}
	
	 
	
	public function onDefault(){
		$data=M("mod_vote")->select(array(
			"where"=>" status=1 ",
			"order"=>"id DESC"
		));
		
		$this->smarty->goassign(array(
			"data"=>$data,
			
		));
		$this->smarty->display("index.html");
	}
	
	 
	
	public function onStatView(){
		$id=get_post("id","i");
		$key="votestatview".$id."_".M("login")->userid;
		if(!cache()->get($key)){
			$vote=M("mod_vote")->selectRow("id=".$id);
			M("mod_vote")->update(array("view_num"=>$vote['view_num']+1),"id=".$id);
			cache()->set($key,3600);
		}
	}
	
	public function onHome(){
		 
		$id=get_post("id","i");
		$vote=M("mod_vote")->selectRow("id=".$id);
		if(empty($vote)){
			$this->goAll("参数出错",1);	
		}
		$vote["imgurl"]=images_site($vote["imgurl"]);
		$vote['content']=M("mod_vote_data")->selectOne(array(
			"where"=>"id=".$id,
			"fields"=>"content"
		));
		 
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->assign(array(
			 
			"skins"=>$this->smarty->template_dir
			
		));
		$this->smarty->goAssign(array(
			"vote"=>$vote,
			"seo"=>array(
				"title"=>$vote['title']
			)
		));
		$this->smarty->display("index.html");
		
	}
	
	public function onAward(){
		$id=get_post("id","i");
		$vote=M("mod_vote")->selectRow("id=".$id);
		if(empty($vote)){
			$this->goAll("参数出错",1);	
		}
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->goassign(array(
			"vote"=>$vote,
			"ulist"=>$ulist,
			"skins"=>$this->smarty->template_dir,
			"seo"=>array(
				"title"=>$vote['title']
			)
		));
		
		$this->smarty->display("vote/award.html");
	}
	
	public function onPaihang(){
		$id=get_post("id","i");
		$vote=M("mod_vote")->selectRow("id=".$id);
		if(empty($vote)){
			$this->goAll("参数出错",1);	
		}
		$vote["imgurl"]=images_site($vote["imgurl"]);
		$option=array(
			"where"=>" vid=".$id." AND status=1 ",
			"order"=>"vote_num DESC",
			"limit"=>100
		);
		$ulist=M("mod_vote_join")->select($option);
		if($ulist){
			foreach($ulist as $k=>$v){
				$v["telephone"]=substr($v["telephone"],0,6)."***".substr($v["telephone"],9);
				$ulist[$k]=$v;
			}
		}
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->goassign(array(
			"vote"=>$vote,
			"ulist"=>$ulist,
			"skins"=>$this->smarty->template_dir,
			"seo"=>array(
				"title"=>$vote['title']
			)
		));
		
		$this->smarty->display("vote/paihang.html");
	}
	
	
	
	public function onDetail(){
		$id=get_post("id","i");
		$vote=M("mod_vote")->selectRow("id=".$id);
		if(empty($vote)){
			$this->goAll("参数出错",1);	
		}
		$vote["imgurl"]=images_site($vote["imgurl"]);
		$vote['content']=M("mod_vote_data")->selectOne(array("where"=>"id=".$id,"fields"=>"content"));
		$this->smarty->template_dir.="/".$vote['tpl'];
		$this->smarty->assign(array(
			 
			"skins"=>$this->smarty->template_dir,
			"seo"=>array(
				"title"=>$vote['title']
			)
		));
		$this->smarty->goassign(array(
			"vote"=>$vote,
			 
		));
		$this->smarty->display("vote/detail.html");
	}
	
	public function onMy(){
		$userid=M("login")->userid;
		
		$this->smarty->display("vote/my.html");
	}
	public function onAdd(){
		
		$this->smarty->display("vote/add.html");
	}
	public function onSave(){
		
	}
	
}

?>