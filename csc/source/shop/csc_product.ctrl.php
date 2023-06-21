<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="/moduleshop.php?m=csc_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$sarr=array("id","supid","isrecommend","ishot","isplan","isfixed");
			foreach($_GET as $k=>$v){
				if($_GET[$k] && in_array($k,$sarr)){
					$v=get($k,'i');
					if($k=="isplan"){
						$v=$v==2?0:$v;
					}
					$where.=" AND $k='".$v."' ";
					$url.="&$k=".urlencode(get($k));
				}
			}
			$bstatus=get("bstatus","i");
			switch($bstatus){
				case 1:
					$where.=" AND status=1 ";
					break;
				case 2:
					$where.=" AND status in(0,2) ";
					break;
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
				$cids=MM("csc","csc_category")->id_family($catid);
				$where.=" AND catid in("._implode($cids).") ";
				$url.="&catid=".$catid;
			}
			$title=get('title','h');
			if($title){
				$where.=" AND title like '%".$title."%'";
				$url.="&title=".urlencode($title);
			}
			$orderby=get("orderby","h");
			switch($orderby){
				case "total_num":
					$order=" total_num ASC";
					break;
				default:
					$order=" id DESC";
					break;
			}
			
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>$order,
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_product")->select($option,$rscount);
			if($data){
				
				foreach($data as $k=>$v){
					$cids[]=$v["catid"];
					$supids[]=$v["supid"];
				}
				$cats=MM("csc","csc_category")->getListByIds($cids);
				$sups=MM("csc","csc_supplier")->getListByIds($supids);
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v["imgurl"]);
					$v["catid_name"]=$cats[$v["catid"]]["title"];
					if(isset($sups[$v["supid"]])){
						$v["sup_title"]=$sups[$v["supid"]]["title"];
					}else{
						$v["sup_title"]="";
					}
					
					$data[$k]=$v;
				}
			}
			$catList=MM("csc","csc_category")->children(0);
			//
			$supList=M("mod_csc_supplier")->select(array(
				"where"=>" status=1 "
			));
			//
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$shop=M("mod_csc_shop")->selectRow(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"shopid,isku"
			));
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"supList"=>$supList,
					"catList"=>$catList,
					"shop"=>$shop
				)
			);
			$this->smarty->display("csc_product/index.html");
		}
	
	public function onPrice(){
		$this->smarty->display("csc_product/price.html");
	}
	public function onPriceSave(){
		$id=get("id","i");
		$data=array();
		$row=M("mod_csc_product")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		} 
		if(isset($_GET["price"])){
			$data["price"]=get("price","h");
			if($row["safe_price"]>$price){
				$this->goAll("商品价格小于保护价",1);
			}
		}
		if(isset($_GET["total_num"])){
			$data["total_num"]=get("total_num","i");
		}
		
		
		M("mod_csc_product")->update($data,"id=".$id);
		$this->goAll("修改成功");
	} 
	public function onAdd(){
			$id=get_post("id","i");
			$imgsdata=array();
			if($id){
				$data=M("mod_csc_product")->selectRow(array("where"=>"id=".$id." AND shopid=".SHOPID));
				$data["content"]=M("mod_csc_product_data")->selectOne(array(
					"where"=>"id=".$id,
					"fields"=>"content"
				));
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
			$catlist=MM("csc","csc_category")->children(0);
			$shop_catlist=MM("csc","csc_shop_product_category")->children(SHOPID,0);
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
			$supList=M("mod_csc_supplier")->select(array(
				"where"=>" status=1 "
			));
			$shop=M("mod_csc_shop")->selectRow(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"shopid,isku"
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist,
				"imgsdata"=>$imgsdata,
				"shop_catlist"=>$shop_catlist,
				"fieldsList"=>$fieldsList,
				"supList"=>$supList,
				"shop"=>$shop
			));
			if($shop["isku"]){
				$this->smarty->display("csc_product/add.html");
			}else{
				$this->smarty->display("csc_product/add_simple.html");
			}
			
		}
		
		public function onSave(){
			$shop=M("mod_csc_shop")->selectRow(array(
				"where"=>" shopid=".SHOPID,
				"fields"=>"shopid,isku"
			));
			if(!$shop["isku"]){
				$this->goAll("无权编辑",1);
			}
			$id=get_post("id","i");
			$unpost=array(
				"videourl","buy_num","view_num"
			);
			$data=M("mod_csc_product")->postData($unpost);
			$data["shopid"]=SHOPID;
			$content=post("content","x");
			$sdata=array(
				"content"=>$content,
				"shopid"=>SHOPID
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
			 //处理视频
			 $videoid=post("videoid","i");
			 if($videoid){
			 	$video=M("mod_csc_video")->selectRow("id=".$videoid);
			 	$data["videourl"]=$video["url"];
			 	$data["videobg"]=$video["imgurl"];
			}
			$cat=M("mod_csc_category")->selectRow("catid=".$data["catid"]);
			$data["ex_table_id"]=$cat["ex_table_id"];
			if($id){
				$row=M("mod_csc_product")->selectRow("id=".$id);
				
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				$data["ex_table_data_id"]=M("table_data")->saveTable($cat["ex_table_id"],$row["ex_table_data_id"]);
				M("mod_csc_product")->update($data,"id='$id'");
				M("mod_csc_product_data")->update($sdata,"id='$id'");
			}else{
				$data["ex_table_data_id"]=M("table_data")->saveTable($cat["ex_table_id"],0);
				$id=M("mod_csc_product")->insert($data);
				$sdata["id"]=$id;
				M("mod_csc_product_data")->insert($sdata);
			}
			$this->goall("保存成功");
		}
		
		public function onSaveSimple(){
			$id=get_post("id","i");
			$arr=array(
				"supid","total_num","isplan","isrecommend","ishot","isfixed"
			);
			$data=array();
			foreach($arr as $key){
				$data[$key]=post($key,"h");
			}
			M("mod_csc_product")->update($data,"id=".$id);
			$this->goall("保存成功");
		}
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_csc_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_csc_product")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_csc_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_csc_product")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_csc_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_csc_product")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
		}
		
		public function onnew(){
			$id=get_post('id',"i");
			$row=M("mod_csc_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$isnew=0;
			if($row["isnew"]==0){
				$isnew=1;
			}
			M("mod_csc_product")->update(array(
				"isnew"=>$isnew
			),"id=".$id);
			$this->goAll("success",0,$isnew);
		}
		public function onhot(){
			$id=get_post('id',"i");
			$row=M("mod_csc_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$ishot=0;
			if($row["ishot"]==0){
				$ishot=1;
			}
			M("mod_csc_product")->update(array(
				"ishot"=>$ishot
			),"id=".$id);
			$this->goAll("success",0,$ishot);
		}
		
		public function onkusave(){
			session_write_close();
			set_time_limit(0);
			$shop=M("mod_csc_shop")->selectRow(array(
				"where"=>" shopid=".SHOPID." ",
				"fields"=>"shopid,pid"
			));
			if(!$shop["pid"]){
				echo "无需同步";
				exit;
			}
			$prolist=M("mod_csc_product")->select(array(
				"where"=>" shopid=".$shop["pid"]
			));
			foreach($prolist as $p){
				$p["shopid"]=SHOPID;
				$p["pid"]=$p["id"];
				$row=M("mod_csc_product")->selectRow("pid=".$p["id"]." AND shopid=".SHOPID);
				$pd=M("mod_csc_product_data")->selectRow("id=".$p["id"]);
				unset($p["id"]);
				unset($p["buy_num"]);
				if($row){
					M("mod_csc_product")->update($p,"id=".$row["id"]);
					$id=$row["id"];
				}else{
					$id=M("mod_csc_product")->insert($p);
				}
				
				if($pd){
					$pd["id"]=$id;
					$pd["shopid"]=SHOPID;
					if($row){
						M("mod_csc_product_data")->update($pd,"id=".$row["id"]);
					}else{
						M("mod_csc_product_data")->insert($pd);
					}
					
				}
			}
			echo "同步成功";
		}
	}

?>