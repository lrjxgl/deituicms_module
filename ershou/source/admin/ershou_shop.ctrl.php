<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class ershou_shopControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$where=" status in(0,1,2)";
			$url="/moduleadmin.php?m=ershou_shop&a=default";
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
			$data=M("mod_ershou_shop")->select($option,$rscount);
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
			$this->smarty->display("ershou_shop/index.html");
		}
		
		public function onAdd(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$data=M("mod_ershou_shop")->selectRow(array("where"=>"shopid=".$shopid));	
			}
			 
			$this->smarty->goassign(array(
				"data"=>$data
				 
			));
			$this->smarty->display("ershou_shop/add.html");
		}
		
		public function onSave(){
			$shopid=get_post("shopid","i");
			$data=M("mod_ershou_shop")->postData();
			if($shopid){
				M("mod_ershou_shop")->update($data,"shopid='$shopid'");
			}else{
				$data['createtime']=date("Y-m-d H:i:s");
				M("mod_ershou_shop")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_ershou_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["status"]==1){
				$status=2;
			}
			M("mod_ershou_shop")->update(array(
				"status"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onRecommend(){
			$shopid=get_post('shopid',"i");
			$row=M("mod_ershou_shop")->selectRow("shopid=".$shopid);
			$status=1;
			if($row["isrecommend"]==1){
				$status=0;
			}
			M("mod_ershou_shop")->update(array(
				"isrecommend"=>$status
			),"shopid=".$shopid);
			$this->goAll("success",0,$status);
		}
		public function onDelete(){
			$shopid=get_post('shopid',"i");
			M("mod_ershou_shop")->update(array("status"=>11),"shopid=$shopid");
			$this->goAll("删除成功");
			 
		}
		
		public function onRecharge(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$shop=M("mod_ershou_shop")->selectRow(array("where"=>"shopid=".$shopid));	
			}
			 
			$this->smarty->goassign(array(
				"shop"=>$shop
				 
			));
			$this->smarty->display("ershou_shop/recharge.html");
		}
		
		public function onRechargeSave(){
			$shopid=get_post("shopid","i");
			if($shopid){
				$shop=M("mod_ershou_shop")->selectRow(array("where"=>"shopid=".$shopid));	
			}
			if($shop["status"]!=1){
				$this->goAll("充值失败",1);
			}
			$money=get("money","i");
			if($money==0){
				$this->goAll("充值失败",1);
			}
			MM("ershou","ershou_shop_money")->addMoney(array(
				"balance"=>$money,
				"shopid"=>$shopid,
				"content"=>"平台人工充值".$money."元"
			));
			$this->goAll("充值成功");
		}
	}

?>