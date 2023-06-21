<?php
class fsbuyModel extends model{
	public $table="mod_fsbuy";
	public function __construct(){
		 
	}
	public function statusList(){
		return array(
			0=>"编辑中",
			1=>"即将开始",
			2=>"进行中",
			3=>"已结束",
			4=>"已取消"
		);
	}
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$res=$this->select(array("where"=>"fsid in("._implode($ids).")"));
		if($res){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['fsid']]=$rs;
			}
			return $data;
		}
	}
	
	public function parseStepConfig($config,$buy_num=0){
		if(empty($config)){
			return [];
		}
		$arr=explode("\r\n",$config);
		
		$list=[];
		foreach($arr as $k=>$v){
			$b=explode(":",$v);
	 
			$list[]=array(
				"num"=>$b[0],
				"price"=>$b[1],
				"active"=>0
			);
		}
		$len=count($list)-1;
		
		for($i=$len;$i>=0;$i--){
			 
			if($list[$i]["num"]<$buy_num){
				$list[$i]["active"]=1;
				break;
			} 
			
		}
		 
		return $list;
	}
	
	public function getStepConfigDiscount($list){
		$discount=100;
		if(empty($list)) return $discount;
		foreach($list as $v){
			if($v["active"]==1){
				return $v["price"];
			}
		}
		return $discount;
	}
	
	
}
?>