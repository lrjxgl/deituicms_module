<?php
class teamwork_shop_product_itemModel extends model{
	public $table="mod_teamwork_shop_product_item";
	public $statusList=array(
		0=>"未分配",
		1=>"处理中",
		2=>"待测试",
		3=>"已完成",
		11=>"已删除"
	);
	public $typeList=array(
			1=>"Bug",
			2=>"改版",
			3=>"新功能",
			4=>"测试",
			5=>"优化"
		);
	public $orderList=array(
		0=>"普通",
		1=>"优先",
		2=>"紧急"
	);	
	public function __construct(&$base=null){
		parent::__construct($base);
	}
	
	public function statusList(){
		return $this->statusList;
	}
	
	public function typelist(){
		return $this->typeList;
	}
	
	public function orderList(){
		return $this->orderList;
	} 
	
	public function getListByIds($ids=array(),$fields="id,title"){
		$res=$this->select(array(
			"where"=>" id in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$data=array();
			foreach($res as $rs){
				$data[$rs['id']]=$rs;
			}
			return $data;
		}
	}
	
	public function Dselect($option=array(),&$rscount=false){
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $v){
				$proids[]=$v['productid'];
			}
			$pros=M("mod_teamwork_shop_product")->getListByIds($proids);
			foreach($data as $k=>$v){
				$v['product']=$pros[$v['productid']];
				$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=$this->statusList[$v['status']];
				$v['type_name']=$this->typeList[$v['typeid']];
				$data[$k]=$v;
			}
			return $data;
		}
	}
	
}
?>