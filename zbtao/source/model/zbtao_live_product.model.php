<?php
class zbtao_live_productModel extends model{
	public $table="mod_zbtao_live_product";
	public function Dselect($ops,&$rscount=false){
		$statusList=array(
			0=>"待审核",
			1=>"已上架",
			2=>"已下架"
		);
		$list=$this->select($ops,$rscount);
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
		} 
		return $list;
	}
	
	public function getByUserid($userid){
		$row=$this->where("userid=?")->row($userid);
		return $row;
	}
	
}