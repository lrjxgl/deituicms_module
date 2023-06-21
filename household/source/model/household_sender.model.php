<?php
class household_senderModel extends model{
	public $table="mod_household_sender";
	public function __construct(){
		parent::__construct();
	}
	public function get($senderid){
		$row=$this->selectRow("senderid=".$senderid);
		if($row){
			$row["userhead"]=images_site($row["userhead"]);
		}
		return $row;
	}
	
	public function getListByIds($ids,$fields="*"){
		$res=$this->select(array(
			"where"=>" senderid in("._implode($ids).") ",
			"fields"=>$fields
		));
		 
		if($res){
			$list=array();
			foreach($res as $v){
				$list[$v["senderid"]]=$v;
			}
			return $list;
		}
	}
	
	public function addMoney($option){
		if(!isset($option["money"]) || !isset($option["senderid"])){
			return false;
		}
		$row=$this->selectRow("senderid=".$option["senderid"]);
		$data=array(
			"money"=>$row["money"]+$option["money"]
		);
		if($option["money"]>0){
			$data["income"]=$row["income"]+$option["money"];
		}
		$this->update($data,"senderid=".$option["senderid"]);
		M("mod_household_sender_moneylog")->insert(array(
			"senderid"=>$option["senderid"],
			"money"=>$option["money"],
			"dateline"=>time(),
			"content"=>$option["content"]."，之前账户{$row["money"]}元,现在".($row["money"]+$option["money"])."元。"
		));
		return true;
	}
	
	public function setCode($sender){
		$key="hshsender".$sender["senderid"]."x".md5(time());
		$key=substr($key,0,32);
		$expire=3600*24*14;
		cache()->set($key,$senderid,$expire);
		setcookie("mhousehold_sender",$key,time()+$expire);
		return $key;
	}
	public function codeLogin(){
		$key=get_post("mhousehold_sender","h");
		if(!$key){
			$key=sql($_COOKIE["mhousehold_sender"]);
		}
		 
		if(empty($key)){
			return false;
		}
		$senderid=cache()->get($key);
		
		$expire=3600*24*14;
		if($senderid){
			$row=$this->selectRow("senderid=".$senderid);
			if($row){
				$_SESSION["mhousehold_sender"]=$senderid;
				setcookie("mhousehold_sender",$key,time()+$expire);
				return true;
			}
		}
		
		return false; 
	}
	
	public function logout(){
		$key=get_post("mhousehold_sender","h");
		if(!$key){
			$key=sql($_COOKIE["mhousehold_sender"]);
		}
		cache()->del($key);
		$_SESSION["mhousehold_sender"]=null;
		unset($_SESSION["mhousehold_sender"]);
	}
	
}