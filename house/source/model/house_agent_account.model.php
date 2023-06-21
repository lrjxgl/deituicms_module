<?php
class house_agent_accountModel extends model{
	public $table="mod_house_agent_account";
	public function __construct(){
		parent::__construct();
	}
	public function get($userid,$fields="*"){
		$row=$this->selectRow("userid=".$userid);
		if(!$row){
			$this->insert(array(
				"userid"=>$userid,
				"fields"=>$fields
			));
			$row=$this->selectRow("userid=".$userid);
		}
		return $row;
	}
	
	public function addMoney($option){
		$userid=$option['userid'];
		$row=$this->selectRow("userid=".$userid);
		if(empty($row)) return false;
		$data=array();
		if(isset($option['money'])){
			$data['money']=$row['money']+$option['money'];			
		}
		$this->update($data,"userid=".$row['userid']);		 
		$logdata=array(
				"dateline"=>time(),
				"userid"=>$userid, 
		);

		if(isset($option['money']) && $option['money']!=0){
			 
			$logdata['money']=$option['money'];
			$logdata['content']=str_replace("[money]",$option['money'],$option['content'])." 原来".$row['money']."元，现在".$data['money']."元";
			M("house_agent_account_log")->insert($logdata);
		}
		 
		
	}
	
}