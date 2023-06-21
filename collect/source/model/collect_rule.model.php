<?php
/**
*Author 雷日锦 362606856@qq.com
*model 自动生成
*/				
class collect_ruleModel extends model{
	 
	public function __construct(){
		parent::__construct();
		 
		$this->table="mod_collect_rule";
	}
	
	public function id_title(){
		$d=$this->select(array());
		if($d){
			foreach($d as $v){
				$data[$v['id']]=$v['title'];
			}
			return $data;
		}
	}
	
	public function moduleList(){
		return array(
			"fenlei"=>"分类信息",
			"forum"=>"论坛",
			"article"=>"文章"
			 
		);
	}
	
}

?>