<?php
class paotuiModel extends model{
	public $table="mod_paotui";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByIds($ids,$fields="*"){
		$res=$this->select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		  
		if($res){
			$list=array();
			$typelist=$this->typelist();
			foreach($res as $v){
				$v["type_name"]=$typelist[$v["typeid"]]["title"];
				$list[$v["ptid"]]=$v;
			}
			return $list;
		}
	}
	
	public function typelist(){
		return array(
			1=>["typeid"=>1,"title"=>"帮取送"],
			3=>["typeid"=>3,"title"=>"帮我买"],
		
			4=>["typeid"=>4,"title"=>"帮排队"],
			5=>["typeid"=>5,"title"=>"帮办事"]
		);
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
}

?>