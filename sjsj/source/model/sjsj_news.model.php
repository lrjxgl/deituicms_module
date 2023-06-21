<?php
class sjsj_newsModel extends model{
	public $table="mod_sjsj_news";
	
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			$uids=[];
			foreach($res as $rs){
				$uids[]=$rs["userid"];
			}
			$sus=MM("sjsj","sjsj_user")->getListByUids($uids);
			foreach($res as $k=>$v){
				$v["suser"]=$sus[$v["userid"]];
				$res[$k]=$v;
			}
		}
		return $res;
	}
	
}