<?php
class paotui_senderModel extends model{
	public $table="mod_paotui_sender";
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
		$ids=array_unique($ids);
		if(empty($ids)){
			return [];
		}
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
		M("mod_paotui_sender_moneylog")->insert(array(
			"senderid"=>$option["senderid"],
			"money"=>$option["money"],
			"dateline"=>time(),
			"content"=>$option["content"]."，之前账户{$row["money"]}元,现在".($row["money"]+$option["money"])."元。"
		));
		return true;
	}
	
	public function setCode($sender){
		$key="ptsender".$sender["senderid"]."x".md5(time());
		$key=substr($key,0,32);
		$expire=3600*24*14;
		cache()->set($key,$sender["senderid"],$expire);
		setcookie("ptsender_code",$key,time()+$expire);
		return $key;
	}
	public function codeLogin(){
		$key=get_post("ptsender_code","h");
		if(!$key){
			$key=sql($_COOKIE["ptsender_code"]);
		}
		if(empty($key)){
			return false;
		}
		$expire=3600*24*14;
		$senderid=cache()->get($key);
		if($senderid){
			$row=$this->selectRow("senderid=".$senderid." AND status in(0,1)");
			if($row){
				cache()->set($key,$senderid,$expire);
				$_SESSION["mpaotui_sender"]=$senderid;
				setcookie("ptsender_code",$key,time()+$expire);
				return true;
			}
		}
		
		return false; 
	}
	
	public function logout(){
		$key=get_post("ptsender_code","h");
		if(!$key){
			$key=sql($_COOKIE["ptsender_code"]);
		}
		cache()->del($key);
		$_SESSION["mpaotui_sender"]=null;
		unset($_SESSION["mpaotui_sender"]);
	}
	
}