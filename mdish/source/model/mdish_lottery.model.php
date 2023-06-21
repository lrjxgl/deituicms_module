<?php
class mdish_lotteryModel extends model{
	public $table="mod_mdish_lottery";
	public function __construct(){
		parent::__construct();
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$res=$this->select(array(
			"where"=>" ltid in("._implode($ids).") ",
			"fields"=>$fields
		));
		$list=array();
		if($res){
			
			foreach($res as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$list[$rs["ltid"]]=$rs;
			}
		}
		return $list;
	}
	
	public function getStatus($status){
		if($status==0){
			return "待审核";
		}elseif($status==1){
			return "已通过";
		}else{
			return "未通过";
		}
	}
	
}