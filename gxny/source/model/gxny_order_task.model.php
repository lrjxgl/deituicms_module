<?php
class gxny_order_taskModel extends model{
	public $table="mod_gxny_order_task";
	public function idList($ops){
		$res=$this->select($ops);
		$list=[];
		if(!empty($res)){
			foreach($res as $rs){
				$list[$rs["taskid"]]=$rs;
			}
		}
		return $list;
	}
	
	public function statusList(){
		$statusList=array(
			0=>"未处理",
			1=>"处理中",
			2=>"待验收",
			3=>"完成"
		);
		return $statusList;
	}
	
}