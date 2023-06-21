<?php
class fsw_joinModel extends model{
	public $table="mod_fsw_join";
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			$uids=[];
			foreach($res as  $v){
				$uids[]=$v["userid"];
			}
			$us=MM("fsw","fsw_user")->getListByIds($uids);
			foreach($res as $k=>$v){
				$v["user_head"]=$us[$v["userid"]]["user_head"];
				//图片
				
				$imgList=[];
				if(!empty($v["imgsdata"])){
					$imgs=explode(",",$v["imgsdata"]);
					foreach($imgs as $img){
						$imgList[]=images_site($img);
					}
				}
				$v["imgList"]=$imgList;
				$res[$k]=$v;
			}
			
		}
		return $res;
		
	}
}