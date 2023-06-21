<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class house_resourceControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$type_name="全部房源";
			switch($type){
				case "new":
					$where=" status=0 ";
					$type_name="待审房源";
					break;
				case "pass":
					$where=" status=1 ";
					$type_name="上架房源";
					break;
				case "forbid":
					$where=" status=2 ";
					$type_name="下架房源";
					break;
				default:
					break;
				
			}
			$snew=get("snew","h");
			switch($snew){
				case "new":
					$where.=" AND isnew=1 ";
					break;
				case "ershou":
					$where.=" AND isnew=0 ";
					break;
			}
			$url="/moduleadmin.php?m=house_resource&type=".$type."&snew=".$snew;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_house_resource")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$taglist=M("mod_house_tags")->select(array(
				"where"=>" status=1"
			));
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"taglist"=>$taglist,
					"type"=>$type,
					"type_name"=>$type_name
				)
			);
			$this->smarty->display("house_resource/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_house_resource")->selectRow(array("where"=>"id=".$id));
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
				"imgsdata"=>$imgsdata
			));
			$this->smarty->display("house_resource/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_house_resource")->postData();
			$data["updatetime"]=date("Y-m-d H:i:s");
			if($id){
				M("mod_house_resource")->update($data,"id='$id'");
			}else{
				M("mod_house_resource")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_house_resource")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_house_resource")->update(array(
				"status"=>$status,
				"updatetime"=>date("Y-m-d H:i:s")
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_house_resource")->update(array(
				"status"=>11,
				"updatetime"=>date("Y-m-d H:i:s")
			),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onTags(){
			$ids=post('ids','i');
			$tagid=post("tagid","i");
			if(!$tagid){
				$this->goAll("请选择归类",1);
			}
			if(empty($ids)){
				$this->goAll("请选择产品",1);
			}
			$hasids=M("mod_house_tags_index")->selectCols(array(
				"where"=>" tagid=".$tagid." AND objectid in("._implode($ids).") ",
				"fields"=>"objectid"
			));
			$newids=$ids;
			if($hasids){
				$newids=array_diff($ids,$hasids);
			}
			if(!empty($newids)){
				foreach($newids as $objectid){
					M("mod_house_tags_index")->insert(array(
						"tagid"=>$tagid,
						"objectid"=>$objectid,
						"orderindex"=>11
					));
				}
			}
			$this->goAll("success");
		}
		
		
	}

?>