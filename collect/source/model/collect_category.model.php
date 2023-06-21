<?php
class collect_categoryModel extends model{
	public $table="mod_fenlei_category";
	public function __construct(){
		parent::__construct();
	}
	public function set($table){
		switch($table){
			case "article":
				$this->table="category";
				break;
			case "forum":
				$this->table="mod_forum_category";
				break;
			default:
				$this->table="mod_fenlei_category";
				break;
		}
		
	}
	public function getListByIds($table,$ids){
		$this->set($table);
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
	public function get($table,$where){
		$this->set($table);
		return $this->selectRow($where);
	}
	public function children($table,$pid=0,$status=1){
		$this->set($table);
		if($status){
			$where=" status=1 ";
		}else{
			$where=" status in(0,1,2) ";
		}
		//$where.=" AND pid=".$pid;
		$fields="catid,title,pid";
		if($this->table=="category"){
			$fields=" catid,cname,pid ";
		}
		 
		$option=array(
			"where"=>$where,
			"fields"=>$fields,
			"order"=>" orderindex ASC"
		);
		$rss=$this->select($option,$rscount);
		 
		if($rss){
			$child=array();
			if($this->table=="category"){
				foreach($rss as $k=>$rs){
					$rs["title"]=$rs["cname"];
					$rss[$k]=$rs;
				}
			}
			
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
		return $catlist;
	}
	
	
}

?>