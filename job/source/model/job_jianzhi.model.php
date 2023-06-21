<?php
class job_jianzhiModel extends model{
	public $table="mod_job_jianzhi";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids){
		$rss=$this->select(array(
			"where"=>" id in("._implode($ids).") "
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				$data[$rs["id"]]=$rs;
			}
			return $data;
		}
	}
}

?>