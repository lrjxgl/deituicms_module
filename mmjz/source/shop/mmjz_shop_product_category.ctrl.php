<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class mmjz_shop_product_categoryControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" shopid=".SHOPID." AND status in(0,1,2)";
			$url="/moduleshop.php?m=mmjz_shop_product_category&a=default";
			$limit=200000;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$rss=M("mod_mmjz_shop_product_category")->select($option,$rscount);
			if($rss){
				foreach($rss as $rs){
					if($rs["pid"]==0){
						$catlist[]=$rs;
					}else{
						$child[$rs["pid"]][]=$rs;
					}
				}
				foreach($catlist as $k=>$v){
					$v["child"]=$child[$v["catid"]];
					$catlist[$k]=$v;
				}
			}
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$this->smarty->goassign(
				array(
					"catlist"=>$catlist,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url
				)
			);
			$this->smarty->display("mmjz_shop_product_category/index.html");
		}
		
		public function onAdd(){
			$catid=get_post("catid","i");
			if($catid){
				$data=M("mod_mmjz_shop_product_category")->selectRow(array("where"=>"catid={$catid}"));	
			}
			
			$catlist=M("mod_mmjz_shop_product_category")->select(array(
				"where"=>" status=1 AND pid=0 AND shopid=".SHOPID
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"catlist"=>$catlist,
			));
			$this->smarty->display("mmjz_shop_product_category/add.html");
		}
		
		public function onSave(){
			$catid=get_post("catid","i");
			$data=M("mod_mmjz_shop_product_category")->postData();
			$data["shopid"]=SHOPID;
			if($catid==$data["pid"]){
				$data["pid"]=0;
			}
			if($catid){
				$row=M("mod_mmjz_shop_product_category")->selectRow("catid=".$catid);
				if($row["shopid"]!=SHOPID){
					$this->goAll("暂无权限",1);
				}
				M("mod_mmjz_shop_product_category")->update($data,"catid='$catid'");
			}else{
				M("mod_mmjz_shop_product_category")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onAddmore(){
			$catid=get('catid','i');
			 
			$data=M("mod_mmjz_shop_product_category")->selectRow(array("where"=>"catid=".$catid));
			 
			if($data["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if(empty($data)) $this->goall("数据出错",1);
			$this->smarty->assign(array(
				"data"=>$data,
				 
			));
			$this->smarty->display("mmjz_shop_product_category/addmore.html");
		}
		public function onSaveMore(){
			$catid=get_post('catid','i');
			$data=M("mod_mmjz_shop_product_category")->selectRow(array("where"=>"catid=".$catid));
			if($data["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			if(empty($data)) $this->goall("数据出错",1);
			$content=post('content');
			$arr=explode("\r\n",$content);
			if($arr){
				foreach($arr as $v){
					$v=trim($v);
					if(!empty($v)){
						$t_d=array(
							 
							"title"=>$v,
							 
							"description"=>$v,
							"pid"=>$data['catid'],
						 
							 
						);
						M("mod_mmjz_shop_product_category")->insert($t_d);
					}
				}
			}
			$this->goall("添加成功");
		}
		public function onStatus(){
			$catid=get_post('catid',"i");
			$row=M("mod_mmjz_shop_product_category")->selectRow("catid=".$catid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_mmjz_shop_product_category")->update(array(
				"status"=>$status
			),"catid=".$catid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$catid=get_post('catid',"i");
			$row=M("mod_mmjz_shop_product_category")->selectRow("catid=".$catid);
			if($row["shopid"]!=SHOPID){
				$this->goAll("暂无权限",1);
			}
			M("mod_mmjz_shop_product_category")->update(array("status"=>11),"catid=$catid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>