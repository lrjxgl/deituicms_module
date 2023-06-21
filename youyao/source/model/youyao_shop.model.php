<?php
class youyao_shopModel extends model{
	public $table="mod_youyao_shop";
	public function __construct(){
		parent::__construct();
	}
	
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$res[$k]=$v;
			}
			return $res;
		}
	}
	
	public function get($shopid,$fields="*"){
		$row=$this->selectRow(array(
			"where"=>" shopid=".$shopid,
			"fields"=>$fields
		));
		if($row){
			$row["imgurl"]=images_site($row["imgurl"]);
		}
		return $row;
	}
	
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)) return false;
		$res=$this->select(array(
			"where"=>"shopid in("._implode($ids).")",
			"fields"=>$fields
		));
		if($res){
			foreach($res as $rs){
				$rs['imgurl']=images_site($rs['imgurl']);
				$data[$rs['shopid']]=$rs;
			}
			return $data;
		}
	}
	
	public function getShopByUserid($userid){
		$row=$this->selectRow(array(
			"where"=>" userid=".$userid,
			"fields"=>$fields
		));
		if($row){
			$row["imgurl"]=images_site($row["imgurl"]);
		}else{
			/*$user=M("user")->getUser($userid);
			$this->insert(array(
				"userid"=>$userid,
				"shopname"=>$user["nickname"]."的小店"
			));
			*/
		}
		return $row;
	}
	
}