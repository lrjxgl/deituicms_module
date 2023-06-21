<?php
class im_msgControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$touserid=get_post("touserid","i");
		$rscount=true;
		$list=MM("im","im_msg")->setTable("mod_im_msg",$userid,MSG_TABLE_NUM)->select(array(
			"where"=>"userid=".$userid." AND touserid=".$touserid
		),$rscount);
		 
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount
		));
		$this->smarty->display("im_msg/index.html");
	}
	
	
	
	public function onSave(){
		$userid=M("login")->userid;
		$touserid=get_post("touserid","i");
		$content=post("content","h");
		$dateline=time();
		M("mod_im_msg_index")->setTable("mod_im_msg_index",$userid,MSG_TABLE_NUM)->delete("userid=".$userid." AND touserid=".$touserid);
		M("mod_im_msg_index")->setTable("mod_im_msg_index",$touserid,MSG_TABLE_NUM)->delete("userid=".$touserid." AND touserid=".$userid);
		
		$indexData=array();
		$indexData=array(
			"userid"=>$userid,
			"touserid"=>$touserid,
			"content"=>$content,
			
			"dateline"=>$dateline
		);
		M("mod_im_msg_index")->setTable("mod_im_msg_index",$userid,MSG_TABLE_NUM)->insert($indexData);
		$indexData=array(
			"userid"=>$touserid,
			"touserid"=>$userid,
			"content"=>$content,
			"dateline"=>$dateline
		);
		M("mod_im_msg_index")->setTable("mod_im_msg_index",$touserid,MSG_TABLE_NUM)->insert($indexData);
		 
		$msgData=array();
		$msgData=array(
			"userid"=>$userid,
			"touserid"=>$touserid,
			"content"=>$content,
			"isme"=>1,
			"dateline"=>$dateline
		);
		MM("im","im_msg")->setTable("mod_im_msg",$userid,MSG_TABLE_NUM)->insert($msgData);
		$msgData=array(
			"userid"=>$touserid,
			"touserid"=>$userid,
			"content"=>$content,
			"isme"=>0,
			"dateline"=>$dateline
		);
		MM("im","im_msg")->setTable("mod_im_msg",$touserid,MSG_TABLE_NUM)->insert($msgData);

		$this->goAll("success");
	}
	
	public function onSaveAI(){
		$userid=M("login")->userid;
		$touserid=get_post("touserid","i");
		$content=post("content","h");
		$dateline=time();
		M("mod_im_msg_index")->delete("(userid=".$userid." AND touserid=".$touserid.") or(userid=".$touserid." AND touserid=".$userid.")" );
		$indexData=array();
		$indexData[]=array(
			"userid"=>$userid,
			"touserid"=>$touserid,
			"content"=>$content,			
			"dateline"=>$dateline
		);
		$indexData[]=array(
			"userid"=>$touserid,
			"touserid"=>$userid,
			"content"=>$content,
			"dateline"=>$dateline
		);
		M("mod_im_msg_index")->insertMore($indexData);
		 
		$msgData=array();
		$msgData[]=array(
			"userid"=>$userid,
			"touserid"=>$touserid,
			"content"=>$content,
			"isme"=>0,
			"dateline"=>$dateline
		);
		$msgData[]=array(
			"userid"=>$touserid,
			"touserid"=>$userid,
			"content"=>$content,
			"isme"=>1,
			"dateline"=>$dateline
		);
		M("mod_im_msg")->insertMore($msgData);
		
		$this->goAll("success");
	}
	
}