<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class exue_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=exue_shop&a=default";
			$keyword=get("keyword","h");
			if($keyword){
				$where.=" AND title like '%".$keyword."%' ";
				$url.="&keyword=".urlencode($keyword);
			}
			$shopid=get("shopid","i");
			if($shopid){
				$where.=" AND shopid=".$shopid;
			}
			$site_index=get("site_index","i");
			if($site_index){
				$where.=" AND site_index=".$site_index;
				$url.="&site_index=1";
			}
			$site_recommend=get("site_recommend","i");
			if($site_recommend){
				$where.=" AND site_recommend=".$site_recommend;
				$url.="&site_recommend=1";
			}
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_exue_shop")->select($option,$rscount);
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
			$this->smarty->display("exue_shop/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_exue_shop")->selectRow(array("where"=>"shopid=".$shopid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("exue_shop/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_exue_shop")->postData();
			if($shopid){
				M("mod_exue_shop")->update($data,"shopid='$shopid'");
			}else{
				M("mod_exue_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_exue_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_exue_shop")->update(array(
				"status"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onsite_recommend(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_exue_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["site_recommend"]==1){
				$status=0;
			}
			M("mod_exue_shop")->update(array(
				"site_recommend"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onsite_index(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_exue_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["site_index"]==1){
				$status=0;
			}
			M("mod_exue_shop")->update(array(
				"site_index"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_exue_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>