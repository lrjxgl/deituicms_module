<?php
class tutor_shop_certModel extends model{
	public $table="mod_tutor_shop_cert";
	public function Dselect($ops,&$rscount=false){
		$list=$this->select($ops,$rscount);
		$statusList=array(
			0=>"待审核",
			1=>"已上架",
			2=>"已下架"
		);
		
		if(!empty($list)){
			foreach($list as $k=>$v){
				$arr=explode(",",$v["imgsdata"]);
				if(!empty($arr)){
					$imgList=[];
					foreach($arr as $p){
						$imgList[]=images_site($p);
					}
					$v["imgslist"]=$imgList;
				}
				$v["status_name"]=$statusList[$v["status"]];
				$list[$k]=$v;
			}
			return $list;
		}
	}
	
	public function typeList(){
		return array(
			1=>"教师证",
			2=>"学士证",
			3=>"硕士证",
			4=>"大学录取通知书",
			5=>"技能证书",
			11=>"其他证书"
		);
	}
	
	
}