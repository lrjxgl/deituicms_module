<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class flk_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=flk_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$sarr=array("id");
			foreach($_GET as $k=>$v){
				if($_GET[$k] && in_array($k,$sarr)){
					$where.=" AND $k='".get($k,'x')."' ";
					$url.="&$k=".urlencode(get($k));
				}
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
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_flk_product")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$cids[]=$v["catid"];
				}
				$cats=MM("flk","flk_category")->getListByIds($cids);
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v["imgurl"]);
					$v["catid_name"]=$cats[$v["catid"]]["title"];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$groupList=M("mod_flk_group")->select(array(
				"where"=>" status=1"
			));	
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"groupList"=>$groupList,
				)
			);
			$this->smarty->display("flk_product/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			$imgsdata=array();
			if($id){
				$data=M("mod_flk_product")->selectRow(array("where"=>"id=".$id));
				$data["content"]=M("mod_flk_product_data")->selectOne(array(
					"where"=>"id=".$id,
					"fields"=>"content"
				));
				$cat=M("mod_flk_category")->selectRow("catid=".$data["catid"]);
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
			$catlist=MM("flk","flk_category")->children(0);
			//增加扩展表
			$fieldsList=array();
			if($cat){
				$tableid=$cat["ex_table_id"];
				
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
			}
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist,
				"catlist"=>$catlist,
				"fieldsList"=>$fieldsList,
				"imgsdata"=>$imgsdata,
			));
			$this->smarty->display("flk_product/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_flk_product")->postData();
			$content=post("content","x");
			$sdata=array(
				"content"=>$content
			);
			//处理imgsdata
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
				}
				
			}
			$cat=M("mod_flk_category")->selectRow("catid=".$data["catid"]);
			
			$data["ex_table_id"]=$cat["ex_table_id"];
			if($id){
				$data["ex_table_data_id"]=M("table_data")->saveTable($cat["ex_table_id"],$row["ex_table_data_id"]);
				M("mod_flk_product")->update($data,"id='$id'");
				M("mod_flk_product_data")->update($sdata,"id='$id'");
			}else{
				$data["ex_table_data_id"]=M("table_data")->saveTable($cat["ex_table_id"],0);
				$id=M("mod_flk_product")->insert($data);
				$sdata["id"]=$id;
				M("mod_flk_product_data")->insert($sdata);
			}
			
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_flk_product")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_flk_product")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_flk_product")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_flk_product")->selectRow("id=".$id);
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_flk_product")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
		}
		
		public function onnew(){
			$id=get_post('id',"i");
			$row=M("mod_flk_product")->selectRow("id=".$id);
			$isnew=0;
			if($row["isnew"]==0){
				$isnew=1;
			}
			M("mod_flk_product")->update(array(
				"isnew"=>$isnew
			),"id=".$id);
			$this->goAll("success",0,$isnew);
		}
		public function onhot(){
			$id=get_post('id',"i");
			$row=M("mod_flk_product")->selectRow("id=".$id);
			$ishot=0;
			if($row["ishot"]==0){
				$ishot=1;
			}
			M("mod_flk_product")->update(array(
				"ishot"=>$ishot
			),"id=".$id);
			$this->goAll("success",0,$ishot);
		}
		
		public function onGroup(){
			$ids=post('ids','i');
			$gid=post("gid","i");
			if(!$gid){
				$this->goAll("请选择归类",1);
			}
			if(empty($ids)){
				$this->goAll("请选择产品",1);
			}
			$hasids=M("mod_flk_group_product")->selectCols(array(
				"where"=>" gid=".$gid." AND productid in("._implode($ids).") ",
				"fields"=>"productid"
			));
			$newids=$ids;
			if($hasids){
				$newids=array_diff($ids,$hasids);
			}
			if(!empty($newids)){
				foreach($newids as $productid){
					M("mod_flk_group_product")->insert(array(
						"gid"=>$gid,
						"productid"=>$productid,
						"orderindex"=>11
					));
				}
			}
			$this->goAll("success");
		}
		
		public function onTable(){
			//增加扩展表
			$catid=get("catid","i");
			$cat=M("mod_flk_category")->selectRow("catid=".$catid);
			$fieldsList=array();
			if($cat){
				$tableid=$cat["ex_table_id"];
				
				if($tableid){
					$fieldsList=M("table_fields")->select(array(
						"where"=>"tableid=".$tableid,
						"order"=>"orderindex ASC"
					));
				}
			}
			$this->smarty->goAssign(array(
				"fieldsList"=>$fieldsList
			));
			$this->smarty->display("flk_product/tablefields.html");
		}
	}

?>