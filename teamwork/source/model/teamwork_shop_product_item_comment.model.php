<?php
class teamwork_shop_product_item_commentModel extends model{
	public $table="mod_teamwork_shop_product_item_comment";
	public $statusList=array(
		0=>"未开始",
		1=>"处理中",
		2=>"已完成"
	);
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function statusList(){
		return $this->statusList;
	}
	
	public function Dselect($option=array(),&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['timeago']=timeago(strtotime($v['createtime']));
				 
				$data[$k]=$v;
			}
			return $data;
		}
	}
	
}
?>