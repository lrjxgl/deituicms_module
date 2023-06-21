<?php
/**
*Author 雷日锦 362606856@qq.com
*model 自动生成
*/				
class collect_typeModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_collect_type";
	}
	
	public function id_title($status=0){
		$where=" 1=1 ";
		if($status) $where.="  AND status=$status ";
		$data=$this->select(array("where"=>$where));
		if($data){
			foreach($data as $k=>$v){
				$t_d[$v['type_id']]=$v['title'];
			}
			return $t_d;
		}
	}
	
}

?>