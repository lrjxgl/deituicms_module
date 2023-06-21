<?php
class sjsj_tagsModel extends model{
	public $table="mod_sjsj_tags";
	public function getListByIds($ids){
		if(empty($ids)){
			return [];
		}
		$ids=array_unique($ids);
		$res=$this->where(" tagid in("._implode($ids).") ")->all();
		$list=[];
		 
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["tagid"]]=$rs;
			}
		}
		return $list;
	}
}