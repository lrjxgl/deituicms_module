<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class gold_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		public function onInit(){
			M("login")->checkLogin();
		}
		public function onDefault(){
			
		}
		public function onOrder(){
			$userid=M("login")->userid;
			$id=post("id");
			$user=M("user")->selectRow("userid=".$userid);
			$product=M("mod_gold_product")->selectRow("id=".$id);
			if($product["gold"]>$user["gold"]){
				$this->goAll("金币不足",1);
			}
			if($product["status"]!=1 || $product["total_num"]==0){
				$this->goAll("产品下架了",1);
			}
			$nickname=post("nickname","h");
			$telephone=post("telephone","h");
			$address=post("address","h");
			if(empty($nickname) || empty($telephone) || empty($address) ){
				$this->goAll("请完善收货信息",1);
			}
			M("user_lastaddr")->add(array(
				"telephone"=>$telephone,
				"address"=>$address,
				"nickname"=>$nickname
			),$userid);
			M("user")->begin();
				M("user")->addMoney(array(
					"userid"=>$userid,
					"gold"=>-$product["gold"],
					"content"=>"您兑换了{$product["title"]}花了{$product['gold']}金币"
				));
				$orderno="go".M("maxid")->get();
				M("mod_gold_order")->insert(array(
					"orderno"=>$orderno,
					"userid"=>$userid,
					"productid"=>$id,
					"gold"=>$product["gold"],
					"nickname"=>$nickname,
					"telephone"=>$telephone,
					"address"=>$address,
					"createtime"=>date("Y-m-d H:i:s")
				));
				M("mod_gold_product")->update(array(
					"total_num"=>$product["total_num"]-1
				),"id=".$id);
			M("user")->commit();
			$this->goAll("兑换成功");
		}
		public function onMy(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2)";
			$url="/module.php?m=gold_order&a=default";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_gold_order")->select($option,$rscount);
			if($data){
				foreach($data as $v){
					$ids[]=$v["productid"];
				}
				$statusList=MM("gold","gold_order")->statusList;
				$pros=MM("gold","gold_product")->getListByIds($ids);
				 
				foreach($data as $k=>$v){
					$v["title"]=$pros[$v["productid"]]["title"];
					$v["imgurl"]=$pros[$v["productid"]]["imgurl"];
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
					"url"=>$url
				)
			);
			$this->smarty->display("gold_order/my.html");
		}
		
		public function onFinish(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$orderid=get("orderid","i");
			 
			$row=MM("gold","gold_order")->where("orderid=?")->row($orderid);
			if($userid!=$row["userid"]){
				$this->goAll("暂无权限",1);
			}
			MM("gold","gold_order")->update(array(
				"status"=>3
			),"orderid=".$orderid);
			$this->goAll("收获成功");
			
		} 
		
	}

?>