<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class pdd_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2) AND shopid=".SHOPID;
			$url="/moduleshop.php?m=pdd_product&a=default";
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
			$data=M("mod_pdd_product")->select($option,$rscount);
			if($data){
				foreach($data as $k=>$v){
					$cids[]=$v["shop_catid"];
				}
				$cats=MM("pdd","pdd_shop_product_category")->getListByIds($cids);
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v["imgurl"]);
					$v["catid_name"]=$cats[$v["shop_catid"]]["title"];
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
			$this->smarty->display("pdd_product/index.html");
		}
	function onWindow(){	 
		$where=" status in(0,1,2) AND shopid=".SHOPID;
		$url="/moduleshop.php?m=pdd_product&a=default";
		$limit=20;
		$start=get("per_page","i");
	 	$keyword=get('keyword','h');
	 	if($keyword){
	 		$where.=" AND title like '%".$keyword."%' ";
	 		$url.="&keyword=".urlencode($keyword);
	 	}
		$start=get('per_page','i');
		$option=array(
			"start"=>$start,
			"limit"=>$limit,
			"order"=>"id DESC",
			"where"=>$where,
		);
		$rscount=true;
		$data=M("mod_pdd_product")->select($option,$rscount);
		if($data){
			
			foreach($data as $k=>$v){
				$v['imgurl']=images_site($v["imgurl"]);
				 
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
				"rscount"=>$rscount,
				"pagelist"=>$pagelist,
			 
			)
		);
		$this->smarty->display("pdd_product/window.html");
	}
	
	public function onWindowSave(){
		$id=get("id","i");
		$row=M("mod_pdd_product")->selectRow("id=".$id);
		if($row["shopid"]!=SHOPID){
			$this->goAll("暂无权限",1);
		}
		$iswindow=0;
		if($row['iswindow']){
			M("mod_pdd_product")->update(array(
				"iswindow"=>0
			),"id=".$id);
			
		}else{
			$ct=M("mod_pdd_product")->selectOne(array(
				"where"=>" iswindow=1 AND shopid=".SHOPID,
				"fields"=>" count(1) as ct"
			));
			if($ct>=3){
				$this->goAll("添加失败,您的橱窗已经满了",1);
			}
			M("mod_pdd_product")->update(array(
				"iswindow"=>1
			),"id=".$id);
			$iswindow=1;
		}
		
		$this->goAll("success",0,$iswindow);
	}
		public function onAdd(){
			$id=get_post("id","i");
			$imgsdata=array();
			if($id){
				$data=M("mod_pdd_product")->selectRow(array("where"=>"id=".$id." AND shopid=".SHOPID));
				$data["content"]=M("mod_pdd_product_data")->selectOne(array(
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
			$catlist=MM("pdd","pdd_category")->children(0);
			$shop_catlist=MM("pdd","pdd_shop_product_category")->children(SHOPID,0);
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist,
				"imgsdata"=>$imgsdata,
				"shop_catlist"=>$shop_catlist
			));
			$this->smarty->display("pdd_product/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_pdd_product")->postData();
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
			if($id){
				$row=M("mod_pdd_product")->selectRow("id=".$id);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_pdd_product")->update($data,"id='$id'");
				M("mod_pdd_product_data")->update($sdata,"id='$id'");
			}else{
				$id=M("mod_pdd_product")->insert($data);
				$sdata["id"]=$id;
				M("mod_pdd_product_data")->insert($sdata);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$row=M("mod_pdd_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_pdd_product")->update(array(
				"status"=>$status
			),"id=".$id);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$row=M("mod_pdd_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_pdd_product")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_pdd_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$isrecommend=0;
			if($row["isrecommend"]==0){
				$isrecommend=1;
			}
			M("mod_pdd_product")->update(array(
				"isrecommend"=>$isrecommend
			),"id=".$id);
			$this->goAll("success",0,$isrecommend);
		}
		
		public function onnew(){
			$id=get_post('id',"i");
			$row=M("mod_pdd_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$isnew=0;
			if($row["isnew"]==0){
				$isnew=1;
			}
			M("mod_pdd_product")->update(array(
				"isnew"=>$isnew
			),"id=".$id);
			$this->goAll("success",0,$isnew);
		}
		public function onhot(){
			$id=get_post('id',"i");
			$row=M("mod_pdd_product")->selectRow("id=".$id);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$ishot=0;
			if($row["ishot"]==0){
				$ishot=1;
			}
			M("mod_pdd_product")->update(array(
				"ishot"=>$ishot
			),"id=".$id);
			$this->goAll("success",0,$ishot);
		}
		
	}

?>