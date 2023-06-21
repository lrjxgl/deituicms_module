<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class csc_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=csc_shop&a=default";
			$limit=20;
			$start=get("per_page","i");
			$shopid=get("shopid","i");
			if($shopid){
				$where.=" AND shopid=".$shopid;
			}
			$shopname=get("shopname","h");
			if($shopname){
				$where.=" AND shopname like '%".$shopname."%' ";
				$url.="&shopname=".urlencode($shopname);
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_csc_shop")->select($option,$rscount);
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
			$this->smarty->display("csc_shop/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_csc_shop")->selectRow(array("where"=>"shopid=".$shopid));	
			}
			$catList=MM("csc","csc_shop_category")->children(0);
			$cityList=M("city")->select(array(
				"where"=>" status=1 "
			));
			$psList=M("mod_csc_shop")->select(array(
				"where"=>" status=1 AND isku=1",
				"fields"=>"shopid,shopname"
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"cityList"=>$cityList,
				"psList"=>$psList
			));
			$this->smarty->display("csc_shop/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_csc_shop")->postData();
			if($shopid){
				M("mod_csc_shop")->update($data,"shopid='$shopid'");
			}else{
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_csc_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_csc_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_csc_shop")->update(array(
				"status"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onRecommend(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_csc_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["isrecommend"]==1){
				$status=0;
			}
			M("mod_csc_shop")->update(array(
				"isrecommend"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onKu(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_csc_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["isku"]==1){
				$status=0;
			}
			M("mod_csc_shop")->update(array(
				"isku"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_csc_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		public function ontongbu(){
			session_write_close();
			set_time_limit(0);
			$shopid=get("shopid","i");
			$shop=M("mod_csc_shop")->selectRow(array(
				"where"=>" shopid=".$shopid." ",
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
				$p["shopid"]=$shopid;
				$p["pid"]=$p["id"];
				$row=M("mod_csc_product")->selectRow("pid=".$p["id"]." AND shopid=".$shopid);
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
					$pd["shopid"]=$shopid;
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