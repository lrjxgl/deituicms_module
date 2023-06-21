<?php
class zbtao_ppModel extends model{
	public $table="mod_zbtao_pp";
	public function Dselect($ops,&$rscount=false){
		$list=$this->select($ops,$rscount);
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		} 
		return $list;
	}
	
	public function getByUserid($userid){
		$row=$this->where("userid=?")->row($userid);
		return $row;
	}
	public function get($ppid,$fields="*"){
		$row=$this->where("ppid=?")->field($fields)->row($ppid);
		if(!empty($row)){
			$row["imgurl"]=images_site($row["imgurl"]);
		}
		return $row;
	}
	
	public function getLogin($return=false){
		$userid=M("login")->userid;
		if(empty($userid)){
			if(!$return){
				C()->goAll("暂无权限",1);
			}
			
			return [];
		}
		$pp=$this->where("userid=?")->row($userid);
		if(empty($pp)){
			if(!$return){
				C()->goAll("暂无权限",1);
			}
			return [];
		}
		 
		return $pp;
		
	}
}