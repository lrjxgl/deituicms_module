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
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php.php?m=jdo2o_article&a=default";
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
			if($id){
				$row=M("mod_jdo2o_article")->selectRow("id=".$id);
				 
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
			
			$status=get_post("status","i");
			M("mod_jdo2o_article")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_jdo2o_article")->selectRow("id=".$id);
			 
			M("mod_jdo2o_article")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>