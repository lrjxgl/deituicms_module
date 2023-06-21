<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ershou_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=ershou_product&a=default";
			$type=get("type","h");
			$type_name="全部商品";
			switch($type){
				case "new":
					$where=" sitecheck=0 ";
					$type_name="待审核";
					break;
				default:
					break;
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" productid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_ershou_product")->select($option,$rscount);
			if(!empty($data)){
				$catids=[];
				foreach($data as $v){
					$catids[]=$v["catid"];
				}
				$cats=MM("ershou","ershou_category")->getListByIds($catids,"catid,title");
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
					$v["catid_title"]=isset($cats[$v["catid"]]["title"])?$cats[$v["catid"]]["title"]:'无';
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"type"=>$type,
					"type_name"=>$type_name
				)
			);
			 
			$this->smarty->display("ershou_product/index.html");
		}
		
		public function onAdd(){
			$productid=get_post("productid","i");
			if($productid){
				$data=M("mod_ershou_product")->selectRow(array("where"=>"productid=".$productid));
				if($data["imgsdata"]){
						$imgs=explode(",",$data["imgsdata"]);
						foreach($imgs as $v){
							$imgsdata[]=array(
								"imgurl"=>$v,
								"trueimgurl"=>images_site($v)
							);
						}
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
			));
			$this->smarty->display("ershou_product/add.html");
		}
		
		public function onSave(){
			$productid=get_post("productid","i");
			$data=M("mod_ershou_product")->postData();
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){
					$data["imgurl"]=$imgsdata[0];
					$data["imgsdata"]=implode(",",$imgsdata);
				}else{
					$data["imgsdata"]="";
				}
				
			}
			if($productid){
				M("mod_ershou_product")->update($data,"productid=".$productid);
			}else{
				M("mod_ershou_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$productid=get_post('productid',"i");
			$row=M("mod_ershou_product")->selectRow("productid=".$productid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_ershou_product")->update(array("status"=>$status),"productid=".$productid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$productid=get_post('productid',"i");
			M("mod_ershou_product")->update(array("status"=>11),"productid=".$productid);
			$this->goAll("删除成功");
			 
		}
		public function onPass(){
			$productid=get_post('productid',"i");
			M("mod_ershou_product")->update(array("sitecheck"=>1),"productid=".$productid);
			$this->goAll("审核成功");
			 
		}
		public function onForbid(){
			$productid=get_post('productid',"i");
			M("mod_ershou_product")->update(array("status"=>4,"sitecheck"=>2),"productid=".$productid);
			$this->goAll("禁止成功");
			 
		}
		
	}

?>