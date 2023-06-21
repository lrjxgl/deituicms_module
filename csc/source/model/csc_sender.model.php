<?php
class csc_senderModel extends model{
	public $table="mod_csc_sender";
	public function __construct(){
		parent::__construct();
	}
	public function get($senderid){
		$row=$this->selectRow("senderid=".$senderid);
		if($row){
			$row["userhead"]=images_site($row["userhead"]);
		}
		return $row;
	}
	public function getListByIds($ids,$fields="*"){
		$ids=array_unique($ids);
		$option=array(
			"where"=>" senderid in("._implode($ids).")",
			"fields"=>$fields
		);
		$rss=$this->select($option);
		if($rss){
			foreach($rss as $rs){
				$rs['userhead']=images_site($rs['userhead']);
				$data[$rs['senderid']]=$rs;
			}
			return $data;
		}
	}
}