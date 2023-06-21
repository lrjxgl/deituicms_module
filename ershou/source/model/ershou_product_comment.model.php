<?php
	class ershou_product_commentModel extends model{
		public $table="mod_ershou_product_comment";
		public function __construct(){
			parent::__construct();
		}
		
		public function getListByTopicId($productid,$limit=12){
			$data=$this->select(array(
				"where"=>"objectid=".$productid,
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
					$v['user_head']=$us[$v['userid']]['user_head'];
					$data[$k]=$v;
				}
			}
			return $data;
		}
	}
?>