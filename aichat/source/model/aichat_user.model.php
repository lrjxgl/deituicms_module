<?php
class aichat_userModel extends model{
	public $table="mod_aichat_user";
	public function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(empty($row)){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=$this->selectRow("userid=".$userid);
		}
		return $row;
	}
	
	public function addToken($ops){
		$userid=$ops["userid"];
		$aiuser=$this->get($userid);
		$this->update(array(
			"token"=>$aiuser["token"]+$ops["num"]
		),"id=".$aiuser["id"]);
		//增加记录
		$content=$ops["content"];
		$content.=",之前".$aiuser["token"]."个，现在".($aiuser["token"]+$ops["num"])."个";
		M("mod_aichat_token_log")->insert(array(
			"userid"=>$ops["userid"],
			"num"=>$ops["num"],
			"content"=>$content,
			"createtime"=>date("Y-m-d H:i:s")
		));
	}
	
	public function addVip($ops){
		$userid=$ops["userid"];
		$aiuser=$this->get($userid);
		$st=time();
		if($aiuser["vip_etime"]>$st){
			$st=$aiuser["vip_etime"];
		}
		$this->update(array(
			"vip_etime"=>$st+$ops["num"]*24*3600
		),"id=".$aiuser["id"]);
		 
	}
	
	 
}