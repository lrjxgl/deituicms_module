<?php
class fenlei_categoryModel extends model{
	public $table="mod_fenlei_category";
	public function __construct(){
		parent::__construct();
	}
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
	public function children($pid=0,$status=1){
		if($status){
			$where=" status=1 ";
		}else{
			$where=" status in(0,1,2) ";
		}
		if($pid>0){
			$where.=" AND pid=".$pid;
		}
		
		$option=array(
			"where"=>$where,
			"order"=>" orderindex ASC"
		);
		$rss=M("mod_fenlei_category")->select($option,$rscount);
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
			return $catlist[$pid];
		}
		$cats=[];
		if(!empty($catlist)){
			foreach($catlist as $c){
				$cats[]=$c;
			}
		}
		return $cats;
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
	public function getTpl($catid,$type=1){
		$catid=intval($catid);
		$data=$this->selectRow("catid=$catid");
		switch($type){
			case 1:
				if($data['listtpl']){
					return $data['listtpl'];
				}else{
					if($data['pid']){
						return self::getTpl($data['pid'],$type);
					}
				}
				break;
			case 2:
				if($data['showtpl']){
					return $data['showtpl'];
				}else{
					if($data['pid']){
						return self::getTpl($data['pid'],$type);
					}
				}
				break;
				
		
		}
	}
	
	public function getUniTpl($catid,$type=1){
		$catid=intval($catid);
		$data=$this->selectRow("catid=$catid");
		switch($type){
			case 1:
				if($data['list_uniapp']){
					return $data['list_uniapp'];
				}else{
					if($data['pid']){
						return self::getUniTpl($data['pid'],$type);
					}else{
						return "";
					}
				}
				break;
			case 2:
				if($data['show_uniapp']){
					return $data['show_uniapp'];
				}else{
					if($data['pid']){
						return self::getUniTpl($data['pid'],$type);
					}else{
						return "";
					}
				}
				break;
				
		
		}
	}
	
	public function parseType($data){
		if(empty($data)){
			return false;
		}
		$arr=explode("\r\n",$data);
		$list=array();
		foreach($arr as $v){
			if(!empty($v)){
				$v=str_replace("&gt;",">",$v);
				$ev=explode("=>",$v);
				if(!empty($ev[1])){
					$list[intval($ev[0])]=htmlspecialchars($ev[1]);
				}
			}
		}
		if(empty($list)){
			return false;
		}else{
			return $list;
		}
	}
}

?>