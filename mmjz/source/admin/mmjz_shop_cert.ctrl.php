<?php
class mmjz_shop_certControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$start=get("per_page","i");
		$limit=12;
		$type=get("type","h");
		$url="/moduleadmin.php?m=mmjz_shop_cert&type=".$type;
		switch($type){
			case "pass":
				$where=" status=1 ";
				break;
			case "forbid":
				$where=" status=2 ";
				break;
			case "all":
				$where="  status in(0,1,2)";
				break;
			default:
				$where=" status=0 ";
				break;
				
		}
		$rscount=true;
		$list=MM("mmjz","mmjz_shop_cert")->Dselect(array(
			"where"=>$where,
			"order"=>"id DESC",
			"limit"=>$limit,
			"start"=>$start
		),$rscount);
		$this->smarty->goAssign(array(
			"list"=>$list,
			"rscount"=>$rscount,
			"type"=>$type
		));
		$this->smarty->display("mmjz_shop_cert/index.html");
	}
	
	public function onAdd(){
		$id=get("id","i");
		$data=[];
		$imgsList=[];
		if($id){
			$data=MM("mmjz","mmjz_shop_cert")->selectRow("id=".$id);
			 
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
		$typeList=MM("mmjz","mmjz_shop_cert")->typeList();
		$this->smarty->goAssign(array(
			"data"=>$data,
			"imgsdata"=>$imgsList,
			"typeList"=>$typeList,
			
		));
		$this->smarty->display("mmjz_shop_cert/add.html");
	}
	
	public function onSave(){
		$id=get_post("id","i");
		$data=M("mod_mmjz_shop_cert")->postData();
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
			$row=M("mod_mmjz_shop_cert")->selectRow("id=".$id);
			 
			M("mod_mmjz_shop_cert")->update($data,"id=".$id);
		}else{
			$data["dateline"]=time();
			$data["shopid"]=SHOPID;
			M("mod_mmjz_shop_cert")->insert($data);
		}
		$this->goAll("success");
	}
	 
	public function onPass(){
		$id=get_post("id","i");
		$row=M("mod_mmjz_shop_cert")->selectRow("id=".$id);
		 
		M("mod_mmjz_shop_cert")->update(array(
			"status"=>1
		),"id=".$id);
		MM("mmjz","mmjz_push")->sendShop(array(
			"shopid"=>$row["shopid"],
			"title"=>"证件审核通知",
			"content"=>"您的证件".$row["title"]."审核通过了"
		));
		$this->goAll("审核通过");
	}
	
	public function onForbid(){
		$id=get_post("id","i");
		$row=M("mod_mmjz_shop_cert")->selectRow("id=".$id);
		 
		M("mod_mmjz_shop_cert")->update(array(
			"status"=>2
		),"id=".$id);
		MM("mmjz","mmjz_push")->sendShop(array(
			"shopid"=>$row["shopid"],
			"title"=>"证件审核通知",
			"content"=>"很遗憾，您的证件".$row["title"]."审核通过失败"
		));
		$this->goAll("审核不通过");
	}
	
	public function onDelete(){
		$id=get_post("id","i");
		$row=M("mod_mmjz_shop_cert")->selectRow("id=".$id);
		 
		M("mod_mmjz_shop_cert")->update(array(
			"status"=>11
		),"id=".$id);
		$this->goAll("删除成功");
	}
	
}