<?php
class fxa_ushareModel extends model{
	
	public $table="mod_fxa_ushare";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,&$rscount=false){
		$list=$this->select($option,$rscount);
		if($list){
			foreach($list as $v){
				$uids[]=$v["userid"];
			}
			$us=M("user")->getUserByIds($uids);
			
			foreach($list as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=images_site($us[$v["userid"]]["user_head"]);
				$list[$k]=$v;
			}
		}
		return $list;		
	}
	
	public function Add($ops){
		$order=MM("fxa","fxa_order")->selectRow("orderid=".$ops["orderid"]);
		if($order["ispay"]==0 || $order["isback"]){
			return false;
		}
		MM("fxa","fxa_order")->update(array(
			"isback"=>1
		),"orderid=".$ops["orderid"]);
		$row=$this->selectRow("userid=".$ops["userid"]." AND productid=".$ops["productid"]);
		MM("fxa","fxa_user")->addMoney(array(
			"userid"=>$ops["userid"],
			"money"=>$ops["money"]
		)); 
		if($row){
			$row["money"]=$row["money"]+$ops["money"];
			$row["num"]++;
			$this->update(array(
				"money"=>$row["money"],
				"num"=>$row["num"]
			),"id=".$row["id"]);
		}else{
			$this->insert(array(
				"userid"=>$ops["userid"],
				"productid"=>$ops["productid"],
				"money"=>$ops["money"],
				"num"=>1
			));
		}
	}
}