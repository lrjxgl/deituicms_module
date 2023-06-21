<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jdo2o_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID."  AND status in(0,1,2)";
			$url="/moduleshop.php?m=jdo2o_article&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jdo2o_article")->select($option,$rscount);
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
			$this->smarty->display("jdo2o_article/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_jdo2o_article")->selectRow(array("where"=>"id=".$id));
				$data=M("mod_jdo2o_article")->selectRow("id=".$id);
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
				$data["true_videourl"]=images_site($data["videourl"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("jdo2o_article/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_jdo2o_article")->postData();
			//处理图片
			if(!checkFileDir($data["imgurl"])){
				$data["imgurl"]="";
			}
			if(!checkFileDir($data["videourl"],"video")){
				$data["videourl"]="";
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
			if($id){
				$row=M("mod_jdo2o_article")->selectRow("id=".$id);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_jdo2o_article")->update($data,"id='$id'");
			}else{
				$data["shopid"]=SHOPID;
				M("mod_jdo2o_article")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_jdo2o_article")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_jdo2o_article")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_jdo2o_article")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if($row["isrecommend"]==1){
				$status=0;
			}else{
				$status=1;
			}
			 
			M("mod_jdo2o_article")->update(array("isrecommend"=>$status),"id=$id");
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_jdo2o_article")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_jdo2o_article")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>