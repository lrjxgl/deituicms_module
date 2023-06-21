<?php
class job_categoryModel extends model{
	public $table="mod_job_category";
	public function getListByIds($ids){
		$rss=$this->select(array(
			"where"=>" catid in("._implode($ids).") "
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
				$data[$rs["catid"]]=$rs;
			}
			return $data;
		}
	}
	public function children($tablename,$pid=0,$status=1){
		$where=" tablename='".$tablename."' ";
		if($status){
			$where.=" AND status=1 ";
		}else{
			$where.=" AND status in(0,1,2) ";
		}
		//$where.=" AND pid=".$pid;
		$option=array(
			"where"=>$where,
			"order"=>" orderindex DESC"
		);
		$rss=$this->select($option,$rscount);
		if($rss){
			$child=array();
			foreach($rss as $rs){
				 
				$rs["imgurl"]=images_site($rs["imgurl"]);
				if($rs["pid"]==0){
					$catlist[$rs["catid"]]=$rs;
				}else{
					$child[$rs["pid"]][]=$rs;
				}
			}
			
			foreach($catlist as $k=>$v){
				$v["child"]=$child[$v["catid"]];
				$catlist[$k]=$v;
			}
		}
		
		if($pid){
			return $catlist[$pid]["child"];
		}
		return $catlist;
	}
	
	public function id_family($id=0){
		$id=intval($id);
		$ids[]=$id;
		$ids1=$this->selectCols(array("where"=>" pid=".$id."  ","fields"=>"catid"));
		if($ids1){
			$ids=array_merge($ids,$ids1);
			
		}
		return $ids;
		
	}
}