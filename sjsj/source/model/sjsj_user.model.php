<?php
class sjsj_userModel extends model{
	
	public   $table="mod_sjsj_user";
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
	public function getListByUids($uids){
		if(empty($uids)){
			return [];
		}
		$uids=array_unique($uids);
		$res=$this->select(array(
			"where"=>" userid in("._implode($uids).") "
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["userid"]]=$rs;
			}
		}
		
		return $list;
	}
	
	public function addMoney($ops){
		$userid=$ops["userid"];
		$money=$ops["money"];
		$content=$ops["content"];
		$su=$this->get($userid);
		M("mod_sjsj_usermoney_log")->insert(array(
			"userid"=>$userid,
			"money"=>$money,
			"content"=>$content
		));
		if($su["money"]>0){
			$this->update(array(
				"income"=>$su["income"]+$money,
				"money"=>$su["money"]+$money
			),"userid=".$userid);
		}else{
			$this->update(array(
				 
				"money"=>$su["money"]+$money
			),"userid=".$userid);
		}
		
	}
	
}