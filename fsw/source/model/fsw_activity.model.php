<?php
class fsw_activityModel extends model{
	public $table="mod_fsw_activity";
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if(!empty($res)){
			$time=time();
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				
				$sday=strtotime($v["sday"]);
				if($time<$sday){
					//即将开始
					$v["atype"]="baoming";
					$v["atype_title"]="报名中";
				}elseif( date("Y-m-d")==substr($v["sday"],0,10) && !$v["isfinish"] ){
					$v["atype"]="doing";
					$v["atype_title"]="比赛中";
				}elseif($time>$sday || $v["isfinish"]==1){
					$v["atype"]="finish";
					$v["atype_title"]="已结束";
				}
				$res[$k]=$v;
			}
		}
		return $res;
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)){
			return false;
		}
		$ids=array_unique($ids);
		$res=$this->Dselect(array(
			"where"=>" actid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=[];
		if(!empty($res)){
			foreach($res as $k=>$v){
				$list[$v["actid"]]=$v;
			}
		}
		return $list;
	}
	
	public function checkAccess($actid,$userid,$act=[]){
		if(empty($act)){
			$act=$this->selectRow("actid=".$actid);
		}
		if($act["userid"]!=$userid){
			return false;
		}
		return true;
	}
}