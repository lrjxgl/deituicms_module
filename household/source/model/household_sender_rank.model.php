<?php
class household_sender_rankModel extends model{
	public $table="mod_household_sender_rank";
	public function get($senderid){
		$row=$this->selectRow("senderid=".$senderid);
		
		if(empty($row)){
			$dgrade=80;
			$rank=M("mod_household_rank")->selectRow("min_grade<".$dgrade." AND status=1 AND max_grade>=".$dgrade." ");
			if(empty($rank)){
				$rank=M("mod_household_rank")->selectRow(array(
					"where"=>"status=1 AND min_grade>=".$dgrade,
					"order"=>"min_grade ASC",
					"limit"=>1
				));
			}
			$data=array(
				"senderid"=>0,
				"grade"=>$dgrade,
				"rankid"=>$rank["rankid"]
			);
			$this->insert($data);
			$data["rank"]=$rank;
			return $data;
		}else{
			$rank=M("mod_household_rank")->selectRow("rankid=".$urank["rankid"]);
			$row["rank"]=$rank;
		}
		return $row;
	}
}