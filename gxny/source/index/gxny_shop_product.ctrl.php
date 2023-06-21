<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gxny_shop_productControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$shopid=MM("gxny","gxny_shop")->inShopid();
			$where=" status in(0,1,2)";
			$url="/module.php?m=gxny_shop_product&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_shop_product")->select($option,$rscount);
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
			$this->smarty->display("gxny_shop_product/index.html");
		}
		
		public function onList(){
			$shopid=MM("gxny","gxny_shop")->inShopid();
			$type=get("type","h");
			switch($type){
				case "free":
					$where=" status=1 AND isused=0 ";
					break;
				case "sold":
					$where=" status=1 AND isused=1 ";
					break;
				case "look":
					$where=" status=1 AND isused=1 AND islook=1 ";
					break;
				default:
					$where=" status =1 ";
					break;
			}
			$where.=" AND shopid=".$shopid;
			$url="/module.php?m=gxny_shop_product&a=list&type=".$type;
			$catid=get("catid","i");
			if($catid){
				$where.=" AND catid=".$catid;
				$url.="&catid=".$catid;
			}
			
			$limit=300;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderindex ASC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_shop_product")->select($option,$rscount);
			$catlist=MM("gxny","gxny_shop_category")->idList(array(
				"where"=>" shopid=".$shopid." AND status=1  "
			));
			if(!empty($data)){
				foreach($data as &$v){
					if(isset($catlist[$v["catid"]])){
						$v["cat"]=$catlist[$v["catid"]];
					}else{
						$v["cat"]=[];
					}
					
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
					"type"=>$type,
					"catList"=>$catlist
				)
			);
			$this->smarty->display("gxny_shop_product/index.html");
		}
		
		public function onShow(){
			$id=get_post("id","i");
			$data=M("mod_gxny_shop_product")->selectRow(array("where"=>"id=".$id));
			$shop=MM("gxny","gxny_shop")->get($data["shopid"]);
			$owner=[];
			if($data["userid"]){
				$owner=M("user")->getUser($data["userid"],"userid,nickname,user_head,follow_num,followed_num");
			}
			$this->smarty->goassign(array(
				"product"=>$data,
				"shop"=>$shop,
				"owner"=>$owner
			));
			$this->smarty->display("gxny_shop_product/show.html");
		}
		
		public function onShop(){
			$shopid=MM("gxny","gxny_shop")->inShopid();
			$this->smarty->goAssign(array(
				"shopid"=>$shopid
			)); 
			$this->smarty->display("gxny_shop_product/shop.html");
		}
		public function onBuy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$user=M("user")->getUser($userid,"userid,money");
			$id=get_post("id","i");
			$pro=M("mod_gxny_shop_product")->selectRow("id=".$id);
			$owner=get_post("owner","i");
			if($pro["isused"] && !$owner){
				$this->goAll("已经被购买了",1);
			}
			$cat=M("mod_gxny_shop_category")->selectRow("catid=".$pro["catid"]);
			if(empty($cat)){
				$this->goAll("数据出错",1);
			}
			$money=$cat["money"];
			if($user["money"]<$money){
				$this->goAll("余额不足，请先充值",22);
			}
			M("mod_gxny_order")->begin();
			M("user")->addMoney(array(
				"userid"=>$userid,
				"money"=>-$money,
				"content"=>"购买菜园#".$pro["no"]."花了".$money."元"
			));
			$orderid=M("mod_gxny_order")->insert(array(
				"userid"=>$userid,
				"shopid"=>$pro["shopid"],
				"productid"=>$id,
				"createtime"=>date("Y-m-d H:i:s"),
				"money"=>$money
			));
			if($owner){
				$etime=strtotime($pro["endtime"]);
				$etime=$etime<time()?time():$etime;
				$endtime=$etime+365*24*3600;
				$starttime=$pro["starttime"];
			}else{
				$endtime=time()+365*24*3600;
				$starttime=date("Y-m-d H:i:s");
			}
			$endtime=date("Y-m-d H:i:s",$endtime);
			M("mod_gxny_shop_product")->update(array(
				"userid"=>$userid,
				"isused"=>1,
				"orderid"=>$orderid,
				"starttime"=>$starttime,
				"endtime"=>$endtime
			),"id=".$id);
			M("mod_gxny_order")->commit();
			
			$this->goAll("success");
		}
		
		public function onMy(){
			M("login")->checkLogin();
			 
			$userid=M("login")->userid;
			$where=" userid=".$userid;
			$url="/module.php?m=gxny_shop_product&a=my";
			 
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" id DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gxny_shop_product")->select($option,$rscount);
			 
			if(!empty($data)){
				$shopids=[];
				foreach($data as $k=>$v){
					$shopids[]=$v["shopid"];
				}
				$sps=MM("gxny","gxny_shop")->getListByIds($shopids);
				foreach($data as $k=>$v){
					$v["shopname"]=$sps[$v["shopid"]]["shopname"];
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
					"type"=>$type,
					"catList"=>$catList
				)
			);
			$this->smarty->display("gxny_shop_product/my.html");
		}
		
	}

?>