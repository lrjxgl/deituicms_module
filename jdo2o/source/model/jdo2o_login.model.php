<?php
class jdo2o_loginModel extends model{
	public $table="mod_jdo2o_admin";
	public $key="asdasd";
	public function __construct(){
		parent::__construct();
	}
	public function setCode($admin){
		require ROOT_PATH."extends/hashids/Hashids.php";
		$hashids = new Hashids\Hashids($this->key,6);
		$icode=$hashids->encode($admin["adminid"]);
		return $icode.".".substr(md5($admin["password"]),0,8);
	}
	public function codeLogin(){
		$code=get("authcode","h");
		$arr=explode(".",$code);
		if(!isset($arr[1])){
			return false;
		}
		require ROOT_PATH."extends/hashids/Hashids.php";
		$hashids = new Hashids\Hashids($this->key,6);
		$adm=$hashids->decode($arr[0]);
		$adminid=$adm[0];
		if(!$adminid){
			return false;
		}
		$admin=$this->selectRow("adminid=".$adminid);
		
		$p=substr(md5($admin["password"]),0,8);
		
		if($p==$arr[1]){
			$_SESSION['mjdo2o_shop_admin']=array(
				"shopid"=>$admin["shopid"],
				"adminname"=>$admin["adminname"],
				"xlasttime"=>$admin["lasttime"],
				"lasttime"=>date("Y-m-d H:i:s"),
				"adminid"=>$admin["adminid"]
			);
			return $admin;
		}
		return false;	
	}
}
?>