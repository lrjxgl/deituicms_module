<?php
class group_tagsModel extends model{
	public $table="mod_group_tags";
	public function __construct(){
		parent::__construct();
	}
	
	public function getList($gid=0,$status=1){
		if($status){
			$where=" status=1 ";
		}else{
			$where=" status<11 ";
		}
		if($gid){
			$where.=" AND gid=".$gid;
		}
		$data=$this->select(array(
			"where"=>$where,
			"order"=>"orderindex asc"
		));
		$sdata=[];
		if($data){
			foreach($data as $v){
				$sdata[$v['tagid']]=$v;
			}
			
		}
		return $sdata;
	}
	
}
?>