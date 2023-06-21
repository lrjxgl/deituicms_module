<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fenleiControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onMenu(){
			$this->smarty->display("menu.html");
		}
		public function onDefault(){
			$where=" status in(0,1,2)";
			$type=get("type","h");
			$type_name="全部信息";
			switch($type){
				case "new":
					$where=" status=0 ";
					$type_name="待审信息";
					break;
				case "pass":
					$where=" status=1 ";
					$type_name="上架信息";
					break;
				case "forbid":
					$where=" status=2 ";
					$type_name="下架信息";
					break;
				default:
					break;
				
			}
			$url="/moduleadmin.php?m=fenlei&type=".$type;
			$limit=20;
			$start=get("per_page","i");
			$sarr=array("id");
			foreach($_GET as $k=>$v){
				if($_GET[$k] && in_array($k,$sarr)){
					$where.=" AND $k='".get($k,'x')."' ";
					$url.="&$k=".urlencode(get($k));
				}
			}
			$hb_on=get("hb_on","i");
			if($hb_on){
				$where.=" AND hb_on=1 ";
				$url.="&hb_on=1";
			}
			$stime=get('stime','h');
			if($stime){
				$where.=" AND createtime>='".$stime."' ";
			}
			$etime=get('etime','h');
			if($etime){
				$where.=" AND createtime<='".$etime."'";
			}
			$catid=get_post('catid','i');
			if($catid){
			 
				$where.=" AND catid=".$catid;
				$url.="&catid=".$catid;
			}
			$title=get('title','h');
			if($title){
				$where.=" AND title like '%".$title."%'";
				$url.="&title=".urlencode($title);
			}
			$isrecommend=get("isrecommend","i");
			if($isrecommend){
				$where.=" AND isrecommend=1 ";
				$url.="&isrecommend=".$isrecommend;
			}
			$isindex=get("isindex","i");
			if($isindex){
				$where.=" AND isindex=1 ";
				$url.="&isindex=".$isindex;
			}
			
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fenlei")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$cids[]=$v["catid"];
				}
				$cats=MM("fenlei","fenlei_category")->getListByIds($cids);
				foreach($data as $k=>$v){
					$v["catid_name"]=$cats[$v["catid"]]["title"];
					$data[$k]=$v;
				}
			}
			$catlist=MM("fenlei","fenlei_category")->children(0);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$taglist=M("mod_fenlei_tags")->select(array(
				"where"=>" status=1"
			));
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"catlist"=>$catlist,
					"taglist"=>$taglist,
					"type"=>$type,
					"type_name"=>$type_name
				)
			);
			$this->smarty->display("fenlei/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_fenlei")->selectRow(array("where"=>"id=".$id));
				 
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
			$catlist=MM("fenlei","fenlei_category")->children(0);
			//增加扩展表
			$tableid=$cat["ex_table_id"];
			$fieldsList=array();
			if($tableid){
				if($data["ex_table_data_id"]){
					$fieldsList=M("table_data")->get($tableid,$data["ex_table_data_id"]);
				}else{
					$fieldsList=M("table_fields")->select(array(
						"where"=>"tableid=".$tableid,
						"order"=>"orderindex ASC"
					));
				}
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"imgsdata"=>$imgsdata,
				"catlist"=>$catlist,
				"fieldsList"=>$fieldsList
				
			));
			$this->smarty->display("fenlei/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_fenlei")->postData();
			$data["imgsdata"]=safeImgsData($data["imgsdata"]);
			if(!empty($data["imgsdata"])){
				$ims=explode(",",$data["imgsdata"]);
				$data["imgurl"]=$ims[0];
			} 
			if($id){
				M("mod_fenlei")->update($data,"id='$id'");
			}else{
				M("mod_fenlei")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_fenlei")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_fenlei")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_fenlei")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");		 
		}
		
		public function onDelAll(){
			$ids=post('ids','i');
			if($ids){
				foreach($ids as $id){
					$id=intval($id);
					M("mod_fenlei")->update(array("status"=>11),"id=".$id);
				}
			}
			$this->goAll("删除成功");
		}
		
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_fenlei")->selectRow("id=".$id);
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_fenlei")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
		}
		
		public function onindex(){
			$id=get_post('id',"i");
			$row=M("mod_fenlei")->selectRow("id=".$id);
			$isindex=0;
			if($row["isindex"]==0){
				$isindex=1;
			}
			M("mod_fenlei")->update(array(
				"isindex"=>$isindex
			),"id=".$id);
			$this->goAll("success",0,$isindex);
		}
		
		public function onCategory(){
			$ids=post('ids','i');
			$catid=post('catid','i');
			if(!$catid) $this->goall("请选择分类",1);
			if($ids){
				foreach($ids as $id){
					$id=intval($ids);
					M("mod_fenlei")->update(array("catid"=>$catid),"id=".$id);
				}
			}
			$this->goall("修改成功");
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
			$hasids=M("mod_fenlei_tags_index")->selectCols(array(
				"where"=>" tagid=".$tagid." AND objectid in("._implode($ids).") ",
				"fields"=>"objectid"
			));
			$newids=$ids;
			if($hasids){
				$newids=array_diff($ids,$hasids);
			}
			if(!empty($newids)){
				foreach($newids as $objectid){
					M("mod_fenlei_tags_index")->insert(array(
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