<?php
	class mscardModel extends model{
		 
		public function __construct(){
			parent::__construct();
			 
			$this->table="mod_mscard";
		}
		
		public function getListByIds($ids){
			if(empty($ids)) return false;
			$data=$this->select(array(
				"where"=>" id in("._implode($ids).")",
				"fields"=>" id,nickname,telephone"
			));
			if($data){
				foreach($data as $v){
					$sdata[$v['id']]=$v;
				}
			}
			return $sdata;
		}
		
	}
?>