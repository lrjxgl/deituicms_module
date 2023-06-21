<?php
class zbtao_pp_ptsModel extends model{
	public $table="mod_zbtao_pp_pts";
	public function Dselect($ops,&$rscount=false){
		$list=$this->select($ops,$rscount);
		$ptcomList=$this->ptcomList();
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["ptcom_name"]=$ptcomList[$v["ptcom"]];
				$list[$k]=$v;
			}
		} 
		return $list;
	}
	
	public function ptcomList(){
		return array(
			"douyin"=>"抖音",
			"taobao"=>"淘宝",
			"pdd"=>"拼多多",
			"weixin"=>"微信直播",
			"kuaishou"=>"快手"
		);
	} 
	
	
	
	
}