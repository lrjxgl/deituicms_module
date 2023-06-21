<?php
class vipcardModel extends model{
	public $table="mod_vipcard";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$rss=$this->select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				 
				$data[$rs["id"]]=$rs;
				 
			}
			return $data;
		}
	}
	
	public function addMoney($ops,$card=false){
		if(!$card){
			$card=$this->selectRow("userid=".$ops["userid"]);
		}
		
		$option=array(
			"money"=>$card["money"]+$ops["money"],
			"updatetime"=>date("Y-m-d H:i:s")
		);
		if($option["money"]>0){
			$option["total_money"]=$card["total_money"]+$option["money"];
		}
		$this->update($option,"id=".$card["id"]);
		M("mod_vipcard_log")->insert(array(
			"userid"=>$ops["userid"],
			"cardid"=>$card["id"],
			"money"=>$ops["money"],	 
			"createtime"=>date("Y-m-d H:i:s"),
			"content"=>$ops["content"]."之前".$card["money"].",现在".($card["money"]+$ops["money"])
		));
		
	}
}

?>