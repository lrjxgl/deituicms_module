<?php
class dodo_recordControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		if(!in_array(get("m"),array("default"))){
			M("login")->checkLogin();
		}
	}
	public function onDefault(){
		$doid=get("doid","i");
		$list=M("mod_dodo_record")->select(array(
			"where"=>"doid=".$doid,
			"order"=>"id DESC",
			"limit"=>100
		));
		if(!empty($list)){
			foreach($list as $k=>$v){
				$v["imgslist"]=array();
				if(!empty($v["imgsdata"])){
					$imarr=explode(",",$v["imgsdata"]);
					$ims=array();
					foreach($imarr as $img){
						$ims[]=images_site($img);
					}
					$v["imgslist"]=$ims;
				}
				$list[$k]=$v;
			}
		}
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onAdd(){
		$doid=get("doid","i");
		$dodo=M("mod_dodo")->selectRow("id=".$doid);
		$this->smarty->goAssign(array(
			"dodo"=>$dodo
		));
		$this->smarty->display("dodo_record/add.html");
	}
	public function onSave(){
		$userid=M("login")->userid;
		$doid=post("doid","i");
		$dodo=M("mod_dodo")->selectRow("id=".$doid);
		if($userid!=$dodo["userid"]){
			$this->goAll("暂无权限",1);
		}
		$content=post("content","h");
		if(empty($content)){
			$this->goAll("内容不能为空",2);
		}
		//处理imgsdata
		$imgsPost=post("imgsdata");
		$imgsdata=""; 
		if(!empty($imgsPost)){
			$ims=explode(",",$imgsPost);
			foreach($ims as $im){
				if($im!="undefined" && $im!=""){
					$imgsarr[]=$im;
				}
			}
			if(!empty($imgsarr)){
				 
				$imgsdata=implode(",",$imgsarr);
			}
			
		} 
		M("mod_dodo_record")->insert(array(
			"content"=>$content,
			"doid"=>$doid,
			"userid"=>$userid,
			"dateline"=>time(),
			"imgsdata"=>$imgsdata
		));
		$this->goAll("保存成功");
	}
}
