<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class bill_logControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid)); 
			$where=" shopid=".$shopid."  ";
			$type=get("type","h");
			switch($type){
				case "back":
					$where.=" AND status=11 ";
					
					break;
				default:
					$where.=" AND status=1 ";
					break;
			}
			
			$url="/module.php?m=bill_log&shopid=".$shopid;
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$where.=" AND catid=".$catid;
				$url.="&catid=".$catid;
			}
			$cdate=get("cdate","h");
			if($cdate){
				$where.=" AND cdate like '".$cdate."%' ";
				$url.="&cdate=".$cdate;
			}
			$sumMoney=M("mod_bill_log")->selectOne(array(
				"where"=>$where,
				"fields"=>" sum(money) as mm "
			));
			$sumMoney=$sumMoney?$sumMoney:0;
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_bill_log")->select($option,$rscount);
			$per_page=$start+$limit;
			$per_page=$per_page>$rscount?0:$per_page;
			$pagelist=$this->pagelist($rscount,$limit,$url);
			$catList=M("mod_bill_shop_category")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"order"=>"catid DESC"
			));
			$income=M("mod_bill_log")->selectOne(array(
				"where"=>" shopid=".$shopid." AND status=1  AND money>0",
				"fields"=>"sum(money) as money"
			));
			$outcome=M("mod_bill_log")->selectOne(array(
				"where"=> "  shopid=".$shopid." AND status=1  AND money<0 ",
				"fields"=>"sum(money) as money"
			));
			$this->smarty->goassign(
				array(
					"list"=>$data,
					"per_page"=>$per_page,
					"pagelist"=>$pagelist,
					"rscount"=>$rscount,
					"url"=>$url,
					"shop"=>$shop,
					"catList"=>$catList,
					"cdate"=>$cdate,
					"income"=>$income,
					"outcome"=>$outcome,
					"sumMoney"=>$sumMoney
				)
			);
			$this->smarty->display("bill_log/index.html");
		}
		
		public function onAdmin(){
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid)); 
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$where=" shopid=".$shopid."  ";
			$type=get("type","h");
			switch($type){
				case "back":
					$where.=" AND status=11 ";
					break;
				default:
					$where.=" AND status=1 ";
					break;
			}
			$url="/module.php?m=bill_log&a=admin";
			$limit=20;
			$start=get("per_page","i");
			$catid=get("catid","i");
			if($catid){
				$where.=" AND catid=".$catid;
				$url.="&catid=".$catid;
			}
			$cdate=get("cdate","h");
			if($cdate){
				$where.=" AND cdate like '".$cdate."%' ";
				$url.="&cdate=".$cdate;
			}
			$sumMoney=M("mod_bill_log")->selectOne(array(
				"where"=>$where,
				"fields"=>" sum(money) as mm "
			));
			$sumMoney=$sumMoney?$sumMoney:0;
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			
			$data=M("mod_bill_log")->select($option,$rscount);
			if($data){
				$catids=array();
				foreach($data as $v){
					$catids[]=$v["catid"];
				}
				$cats=MM("bill","bill_shop_category")->getListByIds($catids);
				foreach($data as $k=>$v){
					$v["catid_title"]=$cats[$v["catid"]]["title"];
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
					"url"=>$url,
					"shop"=>$shop,
					"sumMoney"=>$sumMoney,
					
				)
			);
			$this->smarty->display("bill_log/admin.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_bill_log")->selectRow(array("where"=>"id=".$id));
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("bill_log/show.html");
		}
		public function onAdd(){
			$id=get_post("id","i");
			$shopid=get_post("shopid","i");
			$shop=M("mod_bill_shop")->selectRow(array("where"=>"shopid=".$shopid)); 
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$uList=M("mod_bill_shop_user")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"order"=>"suid DESC"
			));
			$catList=M("mod_bill_shop_category")->select(array(
				"where"=>" shopid=".$shopid." AND status=1 ",
				"order"=>"catid DESC"
			));
			$this->smarty->goassign(array(
				"shop"=>$shop,
				"uList"=>$uList,
				"catList"=>$catList
			));
			$this->smarty->display("bill_log/add.html");
		}
		
		public function onSave(){
			$id=get_post("id","i");
			$data=M("mod_bill_log")->postData();
			if(empty($data["shopid"])){
				$this->goAll("数据出错",1);
			}
			$data["dateline"]=time();
			$data["status"]=1;
			$shopid=$data["shopid"];
			$shop=M("mod_bill_shop")->selectRow("shopid=".$shopid);
			$userid=M("login")->userid;
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			$data["logdesc"]="账户变动".$data["money"]."元，原来".$shop["money"]."元，现在".($shop["money"]+$data["money"])."元";
			M("mod_bill_log")->begin();
			M("mod_bill_log")->insert($data);
			//处理账务金额
			
			if($data["money"]>0){
				M("mod_bill_shop")->update(array(
					"money"=>$shop["money"]+$data["money"],
					"total_money"=>$shop["total_money"]+$data["money"]
				),"shopid=".$shopid);
			}else{
				M("mod_bill_shop")->update(array(
					"money"=>$shop["money"]+$data["money"]					 
				),"shopid=".$shopid);
			}
			//处理成员金额
			if($data["suid"]){
				$suid=$data["suid"];
				$user=M("mod_bill_shop_user")->selectRow(" suid=".$suid);
				if($data["money"]>0){
					M("mod_bill_shop_user")->update(array(
						"money"=>$user["money"]+$data["money"],
						"total_money"=>$user["total_money"]+$data["money"]
					),"suid=".$suid);
				}else{
					M("mod_bill_shop_user")->update(array(
						"money"=>$shop["money"]+$data["money"]					 
					),"suid=".$suid);
				}
			}
			M("mod_bill_log")->commit();
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$id=get_post('id',"i");
			$status=get_post("status","i");
			$row=M("mod_bill_log")->selectRow("id=".$id);
			$userid=M("login")->userid;
			$shop=M("mod_bill_shop")->selectRow("shopid=".$shopid);
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_bill_log")->update(array("status"=>$status),"id=$id");
			$this->goall("状态修改成功");
		}
		
		public function onDelete(){
			$id=get_post('id',"i");
			$shopid=get("shopid","i");
			$row=M("mod_bill_log")->selectRow("id=".$id);
			$userid=M("login")->userid;
			$shop=M("mod_bill_shop")->selectRow("shopid=".$shopid);
			if($shop["userid"]!=$userid){
				$this->goAll("暂无权限",1);
			}
			M("mod_bill_log")->update(array("status"=>11),"id=$id");
			$this->goAll("删除成功");
			 
		}
		
		
	}

?>