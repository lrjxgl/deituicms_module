<?php
	class group_title_loveModel extends model{
		public $table="mod_group_title_love";
		public function __construct(){
			parent::__construct();
		}
		
		public function getListByTopicId($newsid,$limit=12){
			$data=$this->select(array(
				"where"=>"newsid=".$newsid,
				"order"=>"id ASC",
				"limit"=>$limit
			));
			if($data){
				foreach($data as $v){
					$uids[]=$v['userid'];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v['nickname']=$us[$v['userid']]['nickname'];
					$v['user_head']=images_site($us[$v['userid']]['user_head']);
					$data[$k]=$v;
				}
			}
			return $data;
		}
	}
?>