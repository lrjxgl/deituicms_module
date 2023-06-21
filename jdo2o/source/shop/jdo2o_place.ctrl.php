<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jdo2o_placeControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where="shopid=".SHOPID." AND status in(0,1,2) ";
			$url="/moduleshop.php?m=jdo2o_place&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" placeid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jdo2o_place")->select($option,$rscount);
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
			$this->smarty->display("jdo2o_place/index.html");
		}
		
		public function onAdd(){
			$placeid=get_post("placeid","i");
			if($placeid){
				$data=M("mod_jdo2o_place")->selectRow(array("where"=>"placeid=".$placeid));
				 
				if($data["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				
				if(!empty($data['imgsdata'])){
					$imgs=explode(",",$data['imgsdata']);
					foreach($imgs as $v){
						 
						$imgsdata[]=array(
							"trueimgurl"=>images_site($v),
							"imgurl"=>$v
						);
					}
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("jdo2o_place/add.html");
		}
		
		public function onSave(){
			$placeid=get_post("placeid","i");
			$data=M("mod_jdo2o_place")->postData();
			//处理图片
			if(!checkFileDir($data["imgurl"])){
				$data["imgurl"]="";
			}
			if(!checkFileDir($data["mp4url"],"video")){
				$data["mp4url"]="";
			}
			//处理图集
			if(!empty($data['imgsdata'])){
				$imgs=explode(",",$data['imgsdata']);
				$imgsdata=[];
				foreach($imgs as $v){
					if(checkFileDir($v,"attach")){
						$imgsdata[]=$v;
					} 
					
				}
				if(empty($data["imgurl"]) && !empty($imgsdata)){
					$data["imgurl"]=$imgsdata[0];
				}
			}
			if($placeid){
				$row=M("mod_jdo2o_place")->selectRow("placeid=".$placeid);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_jdo2o_place")->update($data,"placeid='$placeid'");
			}else{
				$data["shopid"]=SHOPID;
				M("mod_jdo2o_place")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$placeid=get_post('placeid',"i");
			$row=M("mod_jdo2o_place")->selectRow("placeid=".$placeid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_jdo2o_place")->update(array("status"=>$status),"placeid=$placeid");
			$this->goall("状态修改成功",0,$status);
		}
		public function onRecommend(){
			$placeid=get_post('placeid',"i");
			$row=M("mod_jdo2o_place")->selectRow("placeid=".$placeid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			 
			M("mod_jdo2o_place")->update(array("isrecommend"=>$status),"placeid=$placeid");
			$this->goall("状态修改成功",0,$status);
		}
		public function onDelete(){
			$placeid=get_post('placeid',"i");
			$row=M("mod_jdo2o_place")->selectRow("placeid=".$placeid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_jdo2o_place")->update(array("status"=>11),"placeid=$placeid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>