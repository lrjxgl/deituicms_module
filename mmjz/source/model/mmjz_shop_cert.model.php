<?php
class mmjz_shop_certModel extends model{
	public $table="mod_mmjz_shop_cert";
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
			1=>"营业执照",
			2=>"食品经营许可证",
			3=>"个人身份证",
			4=>"从业健康证",
			5=>"品牌许可证"
		);
	}
	
	
}