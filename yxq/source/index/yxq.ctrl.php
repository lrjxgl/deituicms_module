<?php 
class yxqControl extends skymvc{
	public function onDefault(){
		
		$total_num=M("mod_yxq_paper")->selectOne(array(
			"where"=>" 1 ",
			"fields"=>"count(*) as ct"
		));
		$boy_num=M("mod_yxq_paper")->selectOne(array(
			"where"=>" gender=1 ",
			"fields"=>"count(*) as ct"
		));
		$girl_num=M("mod_yxq_paper")->selectOne(array(
			"where"=>" gender=2 ",
			"fields"=>"count(*) as ct"
		));
		$xzList=MM("yxq","yxq_paper")->xzList();
		$this->smarty->goAssign(array(
			"total_num"=>$total_num,
			"boy_num"=>$boy_num,
			"girl_num"=>$girl_num,
			"xzList"=>$xzList
		));
		$this->smarty->display("yxq/index.html");
	}
}