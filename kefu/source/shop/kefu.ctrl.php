<?php
class kefuControl extends skymvc{
	public $tablename="";
	public $objectid=0;
	public function onInit(){
		
		$openKey=MM("kefu","kefu")->kefuOpenKey();
		$res=cache()->get($openKey);
		$this->tablename=$res["tablename"];
		$this->objectid=intval($res["objectid"]);
		
	}
	public function onDefault(){
		$list=M("mod_kefu")->select(array(
			"where"=>" tablename='".$this->tablename."' AND objectid=".$this->objectid." AND status in(0,1,2)",
			"order"=>"orderindex ASC"
		));
		if($list){
			foreach($list as $k=>$v){
				$v["user_head"]=images_site($v["user_head"]);
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));	
		$this->smarty->display("kefu/index.html");
	}
	
	public function onAdd(){
		$kfid=get("kfid","i");
		$kefu=MM("kefu","kefu")->get($kfid);
		$this->smarty->goAssign(array(
			"kefu"=>$kefu
		));
		$this->smarty->display("kefu/add.html");
	}
	public function onSave(){
		$title=post("title","h");
		$user_head=post("user_head","h");
		$kfid=get_post("kfid","i");
		$es=MM("kefu","kefu")->selectRow("title='".$title."' ");
		if($kfid){
			$row=MM("kefu","kefu")->selectRow("kfid=".$kfid);
			if($es && $title!=$row["title"]){
				$this->goAll("客服名称已存在",1);
			}
			MM("kefu","kefu")->update(array(
				"title"=>$title,
				"user_head"=>$user_head
			),"kfid=".$kfid);
		}else{
			if($es){
				$this->goAll("客服名称已存在",1);
			}
			MM("kefu","kefu")->insert(array(
				"title"=>$title,
				"user_head"=>$user_head,
				"dateline"=>time(),
				"tablename"=>$this->tablename,
				"objectid"=>$this->objectid
			));
		}
		
		$this->goAll("success");
	}
	
	public function onStatus(){
		
	}
	public function onDelete(){
		
	}
	
}
?>