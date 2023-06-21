<?php
class taoke_configControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_taoke_config")->selectRow();
		$this->smarty->assign(array(
			"data"=>$data
		));
		$this->smarty->display("taoke_config/index.html");
	}
	
	public function onSave(){
		$data=M("mod_taoke_config")->postData();
		$row=M("mod_taoke_config")->selectRow();
		$flsets=explode(",",$data["flsets"]);
		$n=0;
		foreach($flsets as $v){
			$n+=$v;
		}
		if($n>100){
			$this->goall("返利率设置不能大于100",1);
		}
		if($row){
			M("mod_taoke_config")->update($data,"id=".$row['id']);
		}else{
			M("mod_taoke_config")->insert($data);
		}
		$this->goAll("保存成功");
	}
	
}
?>