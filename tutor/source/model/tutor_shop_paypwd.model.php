
<?php
class tutor_shop_paypwdModel extends model{
	
	public $table="mod_tutor_shop_paypwd";
	public function __construct(){
		parent::__construct();
	}
	public function get($shopid){
		$row=$this->selectRow("shopid=".$shopid);
		if(empty($row)){
			$this->insert(array(
				"shopid"=>$shopid
			));
			$row=$this->selectRow("shopid=".$shopid);
		}
		return $row;
	}
	
	public function check($shopid,$paypwd){
		$row=$this->get($shopid);
		if($row["paypwd"]!=umd5($paypwd)){
			return false;
		}
		return true;
	}
	
}