<?php
class b2b_shop_certControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$list=MM("b2b","b2b_shop_cert")->Dselect(array(
			"where"=>" shopid=".SHOPID." AND status in(0,1,2)",
			"order"=>"id DESC",
			"limit"=>30
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
		$this->smarty->display("b2b_shop_cert/index.html");
	}
	
	public function onAdd(){
		$id=get("id","i");
		$data=[];
		$imgsList=[];
		if($id){
			$data=MM("b2b","b2b_shop_cert")->selectRow("id=".$id);
			if($data["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if(!empty($data)){
				$arr=explode(",",$data["imgsdata"]);
				foreach($arr as $p){
					$imgsList[]=array(
						"imgurl"=>$p,
						"trueimgurl"=>images_site($p)
					);
				}
			}
		}
		$typeList=MM("b2b","b2b_shop_cert")->typeList();
		$this->smarty->goAssign(array(
			"data"=>$data,
			"imgsdata"=>$imgsList,
			"typeList"=>$typeList
		));
		$this->smarty->display("b2b_shop_cert/add.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$data=M("mod_b2b_shop_cert")->postData();
		$arr=explode(",",$data["imgsdata"]);
		$imgList=[];
		foreach($arr as $v){
			if(substr($v,0,6)=="attach"){
				$imgList[]=$v;
			}
		}
		if(empty($imgList)){
			$this->goAll("请上传图片",1);
		}
		$data["imgsdata"]=implode(",",$imgList);
		if($id){
			$row=M("mod_b2b_shop_cert")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_b2b_shop_cert")->update($data,"id=".$id);
		}else{
			$data["dateline"]=time();
			$data["shopid"]=SHOPID;
			M("mod_b2b_shop_cert")->insert($data);
		}
		$this->goAll("success");
	}
	
	
	public function onDelete(){
		$id=get_post("id","i");
		$row=M("mod_b2b_shop_cert")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		M("mod_b2b_shop_cert")->update(array(
			"status"=>11
		),"id=".$id);
		$this->goAll("删除成功");
	}
	
}