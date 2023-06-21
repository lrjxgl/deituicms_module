<?php
class pinche_lineModel extends model{
	public $table="mod_pinche_line";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" lineid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			foreach($res as $rs){
				$list[$rs["lineid"]]=$rs;
			}
			
		}
		return $list;
	}
	
	public function getBaiTime(){
		$config=M("mod_pinche_config")->selectRow("1");
		//判断是白天还是黑夜
		$arr=explode("-",$config["bai_time"]);
		$b1=explode(":",$arr[0]);
		$b2=explode(":",$arr[1]);
		$h=date("H");//时
		$m=date("i");//分
		//6:00-18:00  8点
		if($h>$b2[0] || $h<$b1[0]){
			$baiTime=0;
		}else if(($h==$b2[0] && $m>$b2[1]) || ($h==$b1[0] && $m<$b1[1] )  ){
			$baiTime=0;
		}else{
			$baiTime=1;
		}
		return $baiTime;
	}
	
}