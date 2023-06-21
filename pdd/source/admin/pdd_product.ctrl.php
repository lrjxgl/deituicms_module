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
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=pdd_product&a=default";
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
					$cids[]=$v["catid"];
				}
				$cats=MM("pdd","pdd_category")->getListByIds($cids);
				foreach($data as $k=>$v){
					$v['imgurl']=images_site($v["imgurl"]);
					$v["catid_name"]=$cats[$v["catid"]]["title"];
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
		
		public function onAdd(){
			$id=get_post("id","i");
			if($id){
				$data=M("mod_pdd_product")->selectRow(array("where"=>"id=".$id));
				$data["content"]=M("mod_pdd_product_data")->selectOne(array(
					"where"=>"id=".$id,
					"fields"=>"content"
				));
			}
			$catlist=MM("pdd","pdd_category")->children(0);
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist
			));
			$this->smarty->display("pdd_product/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_pdd_product")->postData();
			$content=post("content","x");
			$sdata=array(
				"content"=>$content
			);
			if($id){
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
			M("mod_pdd_product")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecommend(){
			$id=get_post('id',"i");
			$row=M("mod_pdd_product")->selectRow("id=".$id);
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