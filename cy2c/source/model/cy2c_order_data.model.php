<?php
class cy2c_order_dataModel extends model{
	public $table="mod_cy2c_order_data";
	public function __construct(){
		parent::__construct();
	}
	
	public function getListByOrderIds($oids){
		$rss=$this->select(array(
			"where"=>" orderid in("._implode($oids).") ",
			"fields"=>"orderid,content"
		));
		$data=array();
		if($rss){
			foreach($rss as $k=>$v){
				$data[$v['orderid']]=json_decode(base64_decode($v['content']),true);
			}
			return $data;
		}
		return false;
	}
	
	public function get($orderid,$new=true){
		$row=$this->selectRow("orderid=".$orderid);
		if(empty($row)) return false;
		$redata=json_decode(base64_decode($row['content']),true);
		if($new){
			$res=M("mod_cy2c_order_product")->select(array(
				"where"=>" orderid=".$orderid
			));
			$statusList=MM("cy2c","cy2c_order_product")->statusList();
			if($res){
				foreach($res as $v){
					$ids[]=$v["productid"];
				}
				$pros=MM("cy2c","cy2c_product")->getListByIds($ids);
				foreach($res as $k=>$v){
					$v["title"]=$pros[$v['productid']]["title"];
					$v["imgurl"]=images_site($pros[$v['productid']]["imgurl"]);
					$v["status_name"]=$statusList[$v["status"]];
					$res[$k]=$v;
				}
			}
			$redata["prolist"]=$res;
		}
		return $redata;
		
	}
	
}
?>