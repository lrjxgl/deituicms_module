<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxa_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=fxa_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			//查询条件
			$id=get("id","i");
			if($id){
				$where.=" AND id=".$id;
				$url.="&id=".$id;
			}
			$status=get("status","h");
			if($status){
				$url.="&status=".$status;
				switch($status){
					case "finish":
						$where.=" AND status=1 ";
						break;
					case "unfinish":
						$where.=" AND status=0";
						break;
				}
			}
			 
			$title=get("title","h");
			if($title){
				$url.="&title=".urlencode($title);
				$where.=" AND title like '%".$title."%' ";
			}
			$shopname=get("shopname","h");
			if($shopname){
				$url.="&shopname=".urlencode($shopname);
				$shop=M("mod_fxa_shop")->selectRow(array(
					"where"=>" title='".$shopname."' ",
					"fields"=>"shopid,title",
				));
				 
				if($shop){
					$where.=" AND shopid=".$shop["shopid"];
				}else{
					$where.=" AND 1=2 ";
				}
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxa_product")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$spids[]=$v["shopid"];
				}
				$sps=MM("fxa","fxa_shop")->getListByIds($spids);
				foreach($data as $k=>$v){
					$v["shopname"]=$sps[$v["shopid"]]["title"];
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
					"url"=>$url
				)
			);
			$this->smarty->display("fxa_product/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fxa_product")->selectRow(array("where"=>"id=".$id));
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
			$shopList=M("mod_fxa_shop")->select(array(
				"where"=>" 1 ",
				"order"=>" shopid DESC",
				"limit"=>100
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
				"shopList"=>$shopList
			));
			$this->smarty->display("fxa_product/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$etime=strtotime(post("etime"));
			$data=M("mod_fxa_product")->postData();
			$data["etime"]=$etime;
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				foreach($ims as $im){
					if($im!="undefined" && $im!=""){
						$imgsdata[]=$im;
					}
				}
				if(!empty($imgsdata)){
	 
					$data["imgsdata"]=implode(",",$imgsdata);
				}
				
			}
			if($id){
				M("mod_fxa_product")->update($data,"id='$id'");
			}else{
				M("mod_fxa_product")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			M("mod_fxa_product")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fxa_product")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>