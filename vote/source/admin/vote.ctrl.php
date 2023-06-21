<?php
class voteControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		 
		 
	}
	
	public function onDefault(){
		$url="/moduleadmin.php?m=vote";
	 
		$start=get('per_page','i');
		$limit=24;
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC"
		);
		$rscount=true;
		$data=MM("vote","mod_vote")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['timeago']=timeago($v['dateline']);
				$data[$k]=$v;
			}
		}
		$pagelist=$this->pagelist($rscount,$limit,$url);
		$this->smarty->assign(array(
			"pagelist"=>$pagelist,
			"rscount"=>$rscount,
			"data"=>$data,
			"typelist"=>MM("vote","mod_vote")->typelist()
		));
		$this->smarty->display("vote/index.html");
	}
	public function onHome(){
		$id=get_post("id","i");
		$vote=M("mod_vote")->selectRow("id=".$id);
		$this->smarty->assign(array(
			"vote"=>$vote
		));
		$this->smarty->display("vote/home.html");
	}
	public function onMenu(){
		$this->smarty->display("menu.html");
	}
	public function onAdd(){
		$id=get_post("id","i");
		if($id){
			$data=M("mod_vote")->selectRow("id=".$id);
			$data['content']=M("mod_vote_data")->selectOne(array("where"=>"id=".$id,"fields"=>"content"));
		}
		$this->smarty->assign(array(
			"data"=>$data,
			"typelist"=>MM("vote","mod_vote")->typelist()		
		));
		$this->smarty->display("vote/add.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		 
		$starttime=strtotime(post('starttime'));
		$endtime=strtotime(post('endtime'));
		$data=M("mod_vote")->postData();
		$data['starttime']=$starttime;
		$data['endtime']=$endtime;
		$_POST["content"]=stripslashes($_POST["content"]);
		$content=sql(post('content','x'));
		if($id){
			M("mod_vote")->update($data,"id=".$id);
			M("mod_vote_data")->update(array("content"=>$content),"id=".$id);
		}else{
			$data['dateline']=time();
			$id=M("mod_vote")->insert($data);
			M("mod_vote_data")->insert(array(
				"content"=>$content,
				"id"=>$id
			));
		}
		$this->goAll("保存成功");
	}
	
	public function onStatus(){
		$id=get_post("id","i");
		$row=M("mod_vote")->selectRow("id=".$id);
		$status=1;
		if($row["status"]==1){
			$status=0;
		}
		M("mod_vote")->update(array("status"=>$status),"id=".$id);
		$this->goAll("保存成功",0,$status);
	}
	public function onDelete(){
		$id=get_post("id","i");
		M("mod_vote")->update(array("status"=>11),"id=".$id);
		$this->goAll("保存成功");
	}
}

?>