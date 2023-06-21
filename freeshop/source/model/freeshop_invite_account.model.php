<?php
class freeshop_invite_accountModel extends model{
	 
	public $table="mod_freeshop_invite_account";
	public function get($userid){
		$row=$this->selectRow("userid=".$userid);
		if(!$row){
			$this->insert(array(
				"userid"=>$userid
			));
			$row=$this->selectRow("userid=".$userid);
		}
		return $row;
	}
	//奖励
	public function add($ops){
		$row=$this->get($ops["userid"]);
		if($ops["money"]>0){
			$data=array(
				"userid"=>$ops["userid"],
				"to_userid"=>$ops["to_userid"],
				"money"=>$ops["money"],
				"productid"=>$ops["productid"],
				"content"=>$ops["content"],
				"dateline"=>time()			
			);
			$ac=array(
				"income"=>$row["income"]+$ops["money"],
				"money"=>$row["money"]+$ops["money"]
			);
		}else{
			$data=array(
				"userid"=>$ops["userid"],				 
				"money"=>$ops["money"],			 
				"content"=>$ops["content"],
				"dateline"=>time()				
			);
			$ac=array(
				 
				"money"=>$row["money"]+$ops["money"]
			);
		} 
		M("mod_freeshop_invite_log")->insert($data);
		
		$this->update($ac,"userid=".$ops["userid"]);
	}
	
}
?>