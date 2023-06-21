<?php
class olprint_openbindControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$where=" shopid=".SHOPID;
		$type=get("type","h");
		if($type=='new'){
			$where.=" AND status =0 ";
		}else{
			$where.=" AND status=1 ";
		}
		$ops=array(
			"where"=>$where,
			"order"=>"id DESC"
		);
		$list=M("mod_olprint_openbind")->select($ops);
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("olprint_openbind/index.html");
	}
	
	public function onPass(){
		$id=get("id","i");
		$row=M("mod_olprint_openbind")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		M("mod_olprint_openbind")->update(array(
			"status"=>1
		),"id=".$id);
		$this->goAll("审核通过");
	}
	
	public function onForbid(){
		$id=get("id","i");
		$row=M("mod_olprint_openbind")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		M("mod_olprint_openbind")->update(array(
			"status"=>4
		),"id=".$id);
		$this->goAll("禁止成功");
		
	}
	
}