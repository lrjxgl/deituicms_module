<?php
 
class kefuModel extends model{
	public $table="mod_kefu"; 
	
	public function kefuOpenKey(){
		$openKey=get_post("kefuOpenKey","h");
		if($openKey){
			return $openKey;
		}elseif(isset($_SESSION["kefuOpenKey"])){
			return $_SESSION["kefuOpenKey"];
		}
		 
		
		return $openKey;
	} 
	public function kefuShopid(){
		$kfid=$_SESSION["kefuShopid"];
		return intval($kfid);
		
	}
	public function getListByIds($ids){
		if(empty($ids)) return false;
		$ids=array_unique($ids);
		$data=$this->select(array("where"=>"kfid in("._implode($ids).") "));
		if($data){
			foreach($data as $k=>$v){
				$v["user_head"]=images_site($v["user_head"]);
				$tdata[$v['kfid']]=$v;
				
			}
			return $tdata;
		}
	}
	
	public function get($kfid){
		$row=$this->selectRow("kfid=".$kfid);
		if(empty($row)) return false;
		$row["user_head"]=images_site($row["user_head"]);
		return $row;
	}
	public function getByTablename($tablename,$objectid){
		$row=$this->selectRow(" tablename='".$tablename."' AND objectid=".$objectid);
		if(empty($row)) return false;
		$row["user_head"]=images_site($row["user_head"]);
		return $row;
	}
	
}
?>