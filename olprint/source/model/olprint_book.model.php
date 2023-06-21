<?php
class olprint_bookModel extends model{
	public $table="mod_olprint_book";
	public function __construct(){
		parent::__construct();
		
	}
	public function statusList(){
		$statusList=array(
			0=>"待接单",
			1=>"待打印",
			2=>"待取货",
			3=>"已完成",
			4=>"已取消"
		);
		return $statusList;
	}
	public function getStatus($status){
		$statusList=$this->statusList();
		return $statusList[$status];
	}
	
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		 
		if($res){
			foreach($res as $k=>$rs){
				 
				$res[$k]=$rs;
			}
		}
		return $res;
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)){
			return false;
		}
		$res=$this->select(array(
			"where"=>" bookid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$list=array();
			foreach($res as $k=>$v){
				$list[$v["bookid"]]=$v;
			}
			return $list;
		}
		
	}
	
}