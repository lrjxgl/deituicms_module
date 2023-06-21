<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class f2c_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/module.php?m=f2c_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$sarr=array("id","isrecommend","ishot","isnew");
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
			$sprice=get("sprice","h");
			$eprice=get("eprice","h");
			if($sprice>0){
				$where.=" AND price>".$sprice;
				$url.="&sprice=".$sprice;
			}
			if($eprice){
				$where.=" AND price<".$eprice;
				$url.="&eprice=".$eprice;
			}
			$type=get("type","h");
			switch($type){
				case "online":
					$where.=" AND status=1 ";
					break;
				case "offline":
					$where.=" AND status in(0,2) ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_f2c_product")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$cids[]=$v["catid"];
				}
				$cats=MM("f2c","f2c_category")->getListByIds($cids);
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v["imgurl"]);
					$v["catid_name"]=$cats[$v["catid"]]["title"];
					$data[$k]=$v;
				}
			}
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$catList=MM("f2c","f2c_category")->children(0);
			$groupList=M("mod_f2c_group")->select(array(
				"where"=>" status=1"
			));	
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"catList"=>$catList,
					"groupList"=>$groupList,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("f2c_product/index.html");
		}
		
		public function onAdd(){
			$id=get_post("id","i");
			$fanlist=MM("f2c","f2c_config_rank")->select(array(
				"where"=>"1",
				"order"=>"min_grade ASC"
			));
			if($id){
				$data=M("mod_f2c_product")->selectRow(array("where"=>"id=".$id));
				$data["content"]=M("mod_f2c_product_data")->selectOne(array(
					"where"=>"id=".$id,
					"fields"=>"content"
				));
				$fls=explode(",",$data['fanli']);
				if(!empty($fls) && !empty($data['fanli'])){
					foreach($fanlist as $k=>$v){
						$v["discount"]=$fls[$k];
						$fanlist[$k]=$v;
					}
				}
			}
			$catlist=MM("f2c","f2c_category")->children(0);
			
			
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist,
				"fanlist"=>$fanlist
			));
			$this->smarty->display("f2c_product/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_f2c_product")->postData();
			$content=post("content","x");
			$sdata=array(
				"content"=>$content
			);
			//返利
			$fl=$data["fanli"]; 
			if(!empty($fl)){
				foreach($fl as $k=>$v){
					$fl[$k]=intval($v);			
				}
				
				if($fl[count($fl)-1]>50){
					$this->goall("返利设置出错",1);
				}
				$data['fanli']=implode(",",$fl); 
			}
			if($id){
				M("mod_f2c_product")->update($data,"id='$id'");
				M("mod_f2c_product_data")->update($sdata,"id='$id'");
			}else{
				$id=M("mod_f2c_product")->insert($data);
				$sdata["id"]=$id;
				M("mod_f2c_product_data")->insert($sdata);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_f2c_product")->selectRow("id=".$id);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_f2c_product")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			M("mod_f2c_product")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_f2c_product")->selectRow("id=".$id);
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_f2c_product")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
		}
		
		public function onnew(){
			$id=get_post('id',"i");
			$row=M("mod_f2c_product")->selectRow("id=".$id);
			$isnew=0;
			if($row["isnew"]==0){
				$isnew=1;
			}
			M("mod_f2c_product")->update(array(
				"isnew"=>$isnew
			),"id=".$id);
			$this->goAll("success",0,$isnew);
		}
		public function onhot(){
			$id=get_post('id',"i");
			$row=M("mod_f2c_product")->selectRow("id=".$id);
			$ishot=0;
			if($row["ishot"]==0){
				$ishot=1;
			}
			M("mod_f2c_product")->update(array(
				"ishot"=>$ishot
			),"id=".$id);
			$this->goAll("success",0,$ishot);
		}
		
		public function onCategory(){
			$ids=post('ids','i');
			$catid=post('catid','i');
			if(!$catid) $this->goall("请选择分类",1);
			if($ids){
				foreach($ids as $id){
					M("mod_f2c_product")->update(array("catid"=>$catid),"id=".$id);
				}
			}
			$this->goall("修改成功");
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
			$hasids=M("mod_f2c_group_product")->selectCols(array(
				"where"=>" gid=".$gid." AND productid in("._implode($ids).") ",
				"fields"=>"productid"
			));
			$newids=$ids;
			if($hasids){
				$newids=array_diff($ids,$hasids);
			}
			if(!empty($newids)){
				foreach($newids as $productid){
					M("mod_f2c_group_product")->insert(array(
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
			$cat=M("mod_f2c_category")->selectRow("catid=".$catid);
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
			$this->smarty->display("f2c_product/tablefields.html");
		}
		
	}

?>