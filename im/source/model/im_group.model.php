<?php
class im_groupModel extends model{
	public $table="mod_im_group";
	public function __construct(){
		parent::__construct();
	}
	
	public function typeList(){
		return array(
			1=>array(
				"id"=>1,
				"title"=>"200人群",
				"maxnum"=>200
			),
			2=>array(
				"id"=>2,
				"title"=>"500人群",
				"maxnum"=>500
			),
			3=>array(
				"id"=>3,
				"title"=>"1000人群",
				"maxnum"=>1000
			),
			4=>array(
				"id"=>4,
				"title"=>"2000人群",
				"maxnum"=>2000
			),
			5=>array(
				"id"=>5,
				"title"=>"万人群",
				"maxnum"=>10000
			),
		);
	}
	
	public function groupList(){
		$list=$this->select(array(
			"where"=>" status=1",
			"limit"=>24,
			"order"=>"orderindex ASC"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$list[$k]=$v;
			}
		}
		return $list;
	}
	public function getListByIds($ids,$fields="*"){
		$res=$this->select(array(
			"where"=>" groupid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($res){
			$list=array();
			foreach($res as $rs){
				$rs["imgurl"]=images_site($rs["imgurl"]);
				$list[$rs["groupid"]]=$rs;
			}
			return $list;
		}
	}
	
}