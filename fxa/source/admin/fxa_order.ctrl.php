<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fxa_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			
			$url="/moduleadmin.php?m=fxa_order&a=default";
			$limit=24;
			$start=get("per_page","i");
			$type=get("type","h");
			switch($type){
				case "all":
					$where=" status in(0,1,2)";
					$type_name="全部订单";
					break;
				case "unpay":
					$where=" ispay=0 AND status=0 ";
					$type_name="待支付";
					break;
				case "unsend":
					$where=" status=1 ";
					$type_name="待发货";
					break;
				case "unreceive":
					$where=" status=2 ";
					$type_name="待收货";
					break;
				case "finish":
					$where=" status=3 ";
					$type_name="已完成";
					break;
				default:
					$where=" status=0 AND ispay=1 ";
					$type_name="新订单";
					break;
			}
			//查询条件
			$orderid=get("orderid","i");
			if($orderid){
				$where.=" AND orderid=".$orderid;
				$url.="&orderid=".$orderid;
			}
			
			 
			$ispay=get("ispay","h");
			if($ispay){
				$url.="&ispay=".$ispay;
				switch($ispay){
					case "finish":
						$where.=" AND ispay=1 ";
						break;
					case "unfinish":
						$where.=" AND ispay=0";
						break;
				}
			}
			$nickname=get("nickname","h");
			if($nickname){
				$url.="&nickname=".urlencode($nickname);
				$user=M("user")->selectRow(array(
					"where"=>" nickname='".$nickname."' ",
					"fields"=>"userid,nickname",
				));
				if($user){
					$where.=" AND userid=".$user["userid"];
				}else{
					$where.=" AND 1=2 ";
				}
			}
			$shopname=get("shopname","h");
			if($shopname){
				$url.="&shopname=".urlencode($shopname);
				$shop=M("mod_fxa_shop")->selectRow(array(
					"where"=>" title='".$shopname."' ",
					"fields"=>"shopid,title",
				));
				 
				if($shop){
					$where.=" AND shopid=".$shop["shopid"];
				}else{
					$where.=" AND 1=2 ";
				}
			}
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fxa_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$spids[]=$v["shopid"];
					$pids[]=$v["productid"];
					$uids[]=$v["userid"];
				}
				$sps=MM("fxa","fxa_shop")->getListByIds($spids);
				$pros=MM("fxa","fxa_product")->getListByIds($pids);
				$us=M("user")->getUserByIds($uids);
				$statusList=MM("fxa","fxa_order")->statusList();
				foreach($data as $k=>$v){
					$v["shopname"]=$sps[$v["shopid"]]["title"];
					$v["title"]=$pros[$v["productid"]]["title"];
					$v["nickname"]=$us[$v["userid"]]["nickname"];
					$v["status_name"]=$statusList[$v["status"]];
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
					"type_name"=>$type_name
				)
			);
			$this->smarty->display("fxa_order/index.html");
		}
		
		public function onShow(){
			$orderid=get_post("orderid","i");
			$data=M("mod_fxa_order")->selectRow(array("where"=>"orderid=".$orderid));
			$statusList=MM("fxa","fxa_order")->statusList();
			$data["status_name"]=$statusList[$data["status"]];
			$user=M("user")->getUser($data["userid"]);
			$inuser=[];
			if($data["invite_userid"]){
				$inuser=M("user")->getUser($data["invite_userid"]);
			} 
			$product=M("mod_fxa_product")->selectRow("id=".$data["productid"]);
			$product["imgurl"]=images_site($product["imgurl"]);
			$this->smarty->goassign(array(
				"data"=>$data,
				"product"=>$product,
				"user"=>$user,
				"inuser"=>$inuser
			));
			$this->smarty->display("fxa_order/show.html");
		}
		 
		public function onSend(){
			$orderid=get_post('orderid',"i");
			M("mod_fxa_order")->update(array("status"=>2),"orderid=$orderid");
			$this->goAll("发货成功");
			 
		}
		public function onUpdateExpress(){
			$orderid=get_post('orderid',"i");
			$express_no=post("express_no","h");
			M("mod_fxa_order")->update(array("express_no"=>$express_no),"orderid=$orderid");
			$this->goAll("更改成功");
			 
		}
		public function onDelete(){
			$orderid=get_post('orderid',"i");
			M("mod_fxa_order")->update(array("status"=>11),"orderid=$orderid");
			$this->goAll("删除成功");
			 
		}
		
		public function onFinish(){
			$orderid=get("orderid",'i');
			$order=M("mod_fxa_order")->selectRow(array("where"=>"orderid=".$orderid));
			 
			if($order["status"]>=3 ){
				$this->goAll("该订单已经处理过了",1);
			}
			M("mod_fxa_order")->update(array(
				"status"=>3,
				"usetime"=>time(),
				"updatetime"=>date("Y-m-d H:i:s")
			),"orderid=".$orderid);
			//处理返利
			if($order["invite_userid"]){
				MM("fxa","fxa_ushare")->add(array(
					"orderid"=>$orderid,
					"userid"=>$order["invite_userid"],
					"money"=>$order["fx_money"],
					"productid"=>$order["productid"],
					"shopid"=>$order["shopid"]
				));
			}
			$this->goAll("完成订单");
		}
		
	}

?>