<?php
	class jieti_categoryModel extends model{
		
		public $table="mod_jieti_category";
		
		public function __construct(){
			parent::__construct();
		}
		
		public function children(){
			$res=$this->select(array(
				"where"=>" status=1 ",
				"order"=>"orderindex asc"
			));
			if($res){
				foreach($res as $v){
					if($v['pid']==0){
						$data[]=$v;
					}else{
						$child[$v['pid']][]=$v;
					}
					
				}
				
				foreach($data as $k=>$v){
					
					$v['child']=$child[$v['catid']];
					 
					$data[$k]=$v;
				}
				 
				return $data;
			}
		}
	}
?>