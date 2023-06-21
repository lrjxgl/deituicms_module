<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class heguan_articleControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=heguan_article&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_heguan_article")->select($option,$rscount);
			if(!empty($data)){
				foreach($data as $k=>$v){
					$v["imgurl"]=images_site($v["imgurl"]);
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
			$this->smarty->display("heguan_article/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_heguan_article")->selectRow(array("where"=>"id=".$id));
				$imgsdata=parseImgsData($data["imgsdata"]);
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("heguan_article/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_heguan_article")->postData();
			if($id){
				M("mod_heguan_article")->update($data,"id=".$id);
			}else{
				M("mod_heguan_article")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_heguan_article")->selectRow("id=".$id);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_heguan_article")->update(array("status"=>$status),"id=".$id);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_heguan_article")->update(array("status"=>11),"id=".$id);
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>