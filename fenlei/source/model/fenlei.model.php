<?php
class fenleiModel extends model{
	public $table="mod_fenlei";
	public function __construct(){
		parent::__construct();
	}
	
	public function priceList($catid=0){
		$data=array(
			"500以下",
			"500-1000元",
			"10000-1500元",
			"5000元以上"
		);
		
		if($catid){
			$row=M("mod_fenlei_category")->selectRow("catid=".$catid);
			if(!empty($row["pricedata"])){
				$arr=explode("\r\n",$row["pricedata"]);
				$data=array();
				foreach($arr as $v){
					if(!empty($v)){
						$data[]=html($v);
					}
				}
			}elseif($row["pid"]){
				$row=M("mod_fenlei_category")->selectRow("catid=".$row["pid"]);
				if(!empty($row["pricedata"])){
					$arr=explode("\r\n",$row["pricedata"]);
					$data=array();
					foreach($arr as $v){
						if(!empty($v)){
							$data[]=html($v);
						}
					}
				}
			}
		}
		return $data;
	}
	
	public function hblist(){
		$list=array(
			
		);
		return $list;
	}
	
	public function getListByIds($ids){
		$ids=array_unique($ids);
		$res=$this->Dselect(array(
			"where"=>" id in("._implode($ids).") "
		));
		if($res){
			foreach($res as $rs){
				$list[$rs["id"]]=$rs;
			}
			return $list;
		}
	}
	public function Dselect($option,&$rscount=false){
		if(!isset($option["fields"])){
			$option["fields"]="id,title,userid,createtime,imgsdata,status,imgurl,money,description";
		}
		$data=$this->select($option,$rscount);
		if($data){
			foreach($data as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				if($v["imgsdata"]){
					$ims=explode(",",$v["imgsdata"]);
					$imgsdata=array();
					foreach($ims as $im){
						$imgsdata[]=images_site($im);
					}
					$v["imgsdata"]=$imgsdata;
				}
				$data[$k]=$v;
			}
		} 
		return $data;
	}
	
	
	
}

?>