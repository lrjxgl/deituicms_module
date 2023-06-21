<?php
class teamwork_shop_productModel extends model{
	public $table="mod_teamwork_shop_product";
	public $statusList=array(
		0=>"未审核",
		1=>"已上线",
		2=>"已下线"
	);
	public function checkAccess($productid,$userid,$accessType="view"){
		$product=M("mod_teamwork_shop_product")->selectRow("id=".$productid);
		$puser=M("mod_teamwork_shop_product_user")->selectRow("productid=".$productid." AND userid=".$userid);
		switch($accessType){
			case "view":
				
				if(!$puser && $product['userid']!=$userid ){
					C()->goAll("暂无权限",1,0,"/");
				}
				break;
		}
		return true;
	}
	 
	
	public function statusList(){
		return $this->statusList;
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
			foreach($data as $k=>$v){
				$v['timeago']=timeago(strtotime($v['createtime']));
				$v['status_name']=$this->statusList[$v['status']];
				$data[$k]=$v;
			}
			return $data;
		}
	}
	
}
?>