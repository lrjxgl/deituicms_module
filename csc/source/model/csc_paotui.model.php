<?php
class csc_paotuiModel extends model{
	public $table="mod_csc_paotui";
	public function __construct(){
		parent::__construct();
	}
	public function status_list(){
		return array(
			0=>"待接单",
			1=>"办理中",
			2=>"待验收",
			3=>"已完成",
			8=>"已取消"
		);
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		if(empty($ids)){
			return false;
		}
		$option=array(
			"where"=>" id in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				
				$data[$rs['id']]=$rs;
			}
			return $data;
		}
	}
}