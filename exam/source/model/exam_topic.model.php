<?php
class exam_topicModel extends model{
	public $table="mod_exam_topic";
	public function __construct(){
		parent::__construct();
	}
	
	public function typeList(){
		return array(
			"radio"=>"单选题",
			"checkbox"=>"多选题",
			"text"=>"填空题",
			"textarea"=>"解答题"
		);
	}
	public function getListByIds($ids,$fields="topicid,title,typeid"){
		$ids=array_unique($ids);
		$rss=$this->select(array(
			"where"=>" topicid in("._implode($ids).") ",
			"fields"=>$fields
		));
		if($rss){
			$data=array();
			foreach($rss as $rs){
			 
				$data[$rs["topicid"]]=$rs;
				 
			}
			return $data;
		}
	}
}

?>