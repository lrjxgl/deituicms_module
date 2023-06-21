<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class jdo2o_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=jdo2o_shop&a=default";
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
			$isrecommend=get("isrecommend","i");
			if($isrecommend){
				$where.=" AND isrecommend=1 ";
				$url.="&isrecommend=1";
			}
			$yystatusType=get("yystatusType",'h');
			switch($yystatusType){
				case "unbegin":
					$where.=" AND yystatus=0 ";
					break;
				case "doing":
					$where.=" AND yystatus=1 ";
					break;
				case "finish":
					$where.=" AND yystatus=2 ";
					break;
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" shopid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_jdo2o_shop")->select($option,$rscount);
			if($data){
				$yystatusList=array("待营业","营业中","已休息");
				foreach($data as $k=>$v){
					$v["yystatus_name"]=$yystatusList[$v["yystatus"]];
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
			$this->smarty->display("jdo2o_shop/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_jdo2o_shop")->selectRow(array("where"=>"shopid=".$shopid));	
			}
			$catList=MM("jdo2o","jdo2o_shop_category")->children(0);
			$pList=M("mod_jdo2o_shop")->select(array(
				"where"=>"istown=1 AND status=1 ",
			));
			$this->smarty->goassign(array(
				"data"=>$data,
				"catList"=>$catList,
				"pList"=>$pList
			));
			$this->smarty->display("jdo2o_shop/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_jdo2o_shop")->postData();
			if($shopid){
				M("mod_jdo2o_shop")->update($data,"shopid='$shopid'");
			}else{
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_jdo2o_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_jdo2o_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_jdo2o_shop")->update(array(
				"status"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onRecommend(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_jdo2o_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["isrecommend"]==1){
				$status=0;
			}
			M("mod_jdo2o_shop")->update(array(
				"isrecommend"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_jdo2o_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		public function ongoShop(){
			$shopid=get_post('shopid',"i");
		 
			$_SESSION["mjdo2o_shop_admin"]=array(
				"shopid"=>$shopid,
				"username"=>"admin",
				"isfounder"=>1
			);
			$this->goAll("登录成功",0,0,"/moduleshop.php?m=jdo2o");
		}
		
		
	}

?>