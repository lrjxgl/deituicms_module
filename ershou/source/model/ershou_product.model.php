<?php
class ershou_productModel extends model{
	
	public $table="mod_ershou_product";
	public function Dselect($option=array(),&$rscount=false){
		$res=$this->select($option,$rscount);
		if($res){
			 
			foreach($res as $v){
				$uids[]=$v["userid"];
				 
			}
			$us=M("user")->getUserByIds($uids,"userid,nickname,user_head");
			 
			foreach($res as $k=>$v){
				$v["user"]=$us[$v["userid"]];
				 
				if($v['imgsdata']){
					$imgs=explode(",",$v['imgsdata']);
					$imgslist=array();
					foreach($imgs as $img){
						$imgslist[]=images_site($img);
					}
					$v['imgList']=$imgslist;
					$v["imgurl"]=images_site($v["imgurl"]);
				}
				$v["timeago"]=timeago(strtotime($v["updatetime"]));
				$res[$k]=$v;
			}
			
		}
		return $res;
	}
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$res=$this->Dselect(array("where"=>"productid in("._implode($ids).")"));
		$data=[];
		if(!empty($res)){
			foreach($res as $rs){
			 
				$data[$rs['productid']]=$rs;
			}
			
		}
		return $data;
	}
	
	public function getDataById($id){
		$row=$this->selectRow("productid=".$id);
		if(!empty($row)){
			$row["imgurl"]=images_site($row["imgurl"]);
		}
		return $row;
	}
	
}