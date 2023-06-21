<?php
	class fenlei_companyModel extends model{
		public $table="mod_fenlei_company";
		public function __construct(){
			parent::__construct();
		}
		
		public function get($userid){
			$data=$this->selectRow("userid=".$userid);
			if(!$data){
				$this->insert(array(
					"userid"=>$userid
				));
				$data=$this->selectRow("userid=".$userid);
			}
			return $data;
		}
		
	}