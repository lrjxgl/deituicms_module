<?php
class ershou_historyModel extends model{
	public $table="mod_ershou_history";
	public function add($ops=[]){
		$userid=$ops["userid"];
		$tablename=$ops["tablename"];
		$objectid=$ops["objectid"];
		$row=$this->selectRow("userid=".$userid." AND tablename='".$tablename."' AND objectid=".$objectid." ");
		if($row){
			$this->update(array(
				"createtime"=>date("Y-m-d H:i:s")
			),"id=".$row["id"]);
		}else{
			$this->insert(array(
				"userid"=>$userid,
				"tablename"=>$tablename,
				"objectid"=>$objectid,
				"createtime"=>date("Y-m-d H:i:s")
			));
			
		}
	} 
}