<?php
class tutor_lessonModel extends model{
	public $table="mod_tutor_lesson";
 
	public function Dselect($ops,&$rscount=false){
		$res=$this->select($ops,$rscount);
		if($res){
			foreach($res as $k=>$v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$res[$k]=$v;
			}
		}
		return $res;
	}
	public function get($lessonid,$fields="*"){
		$book=$this->selectRow(array(
			"where"=>" lessonid=".$lessonid,
			"fields"=>$fields
		));
		if($book){
			$book["imgurl"]=images_site($book["imgurl"]);
		}
		return $book;
	}
	public function getListByIds($ids,$fields="*"){
		if(empty($ids)) return false;
		$data=$this->select(array(
			"where"=>"lessonid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($data){
			foreach($data as $v){
				$v["imgurl"]=images_site($v["imgurl"]);
				$ndata[$v['lessonid']]=$v;				
			}
			return $ndata;
		}
		
	}
	 
	
}
?>