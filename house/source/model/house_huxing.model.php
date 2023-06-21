<?php
class house_huxingModel extends model{
	
	public $table="mod_house_huxing";
	public function __construct(){
		parent::__construct();
	}
	public function Dselect($option,&$rscount=false){
		$data=M("mod_house_huxing")->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v['imgurl']);
				if(!empty($v["imgsdata"])){
					$imgs=explode(",",$v["imgsdata"]);
					foreach($imgs as $kk=>$im){
						$imgs[$kk]=images_site($im);
					}
				}
				$v["imglist"]=$imgs;
				$data[$k]=$v;
			}
		}
		return $data;
	}
	
}