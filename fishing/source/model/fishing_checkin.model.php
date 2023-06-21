<?php
class fishing_checkinModel extends model{
	public $table="mod_fishing_checkin";
	public function Dselect($ops,&$rscount=false){
		$data=$this->select($ops,$rscount);
		if(!empty($data)){
			$uids=[];
			$placeids=[];
			foreach($data as $v){
				$uids[]=$v["userid"];
				$placeids[]=$v["placeid"];
			}
			$places=MM("fishing","fishing_place")->getListByIds($placeids);
			$us=M("user")->getUserByIds($uids);
			foreach($data as $k=>$v){
				$v["nickname"]=$us[$v["userid"]]["nickname"];
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				$v["timeago"]=timeago(strtotime($v["createtime"]));
				$v["imgsList"]=[];
				if(!empty($v["imgsdata"])){
					$ims=explode(",",$v["imgsdata"]);
					foreach($ims as $imkey=>$imv){
						$ims[$imkey]=images_site($imv);
					}
					$v["imgsList"]= $ims;
				}
				$v["place"]=$places[$v["placeid"]];
				$data[$k]=$v;
			}
		}
		return $data;
	}
}