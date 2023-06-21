<?php
	/**
	*Author 雷日锦 362606856@qq.com 
	*控制器自动生成
	*/
	if(!defined("ROOT_PATH")) exit("die Access ");
	class fishing_free_orderControl extends skymvc{
		
		public function __construct(){
			parent::__construct();
			 
		}
		
		public function onDefault(){
			$placeid=get("placeid","i");
			$where=" status in(0,1,2) AND ispay=1 ";
			if($placeid){
				$where.=" AND placeid=".$placeid;
			}
			$url="/module.php?m=fishing_free_order&placeid=".$placeid;
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_free_order")->select($option,$rscount);
			if(!empty($data)){
				foreach($data as $v){
					$uids[]=$v["userid"];
				}
				$us=M("user")->getUserByIds($uids);
				foreach($data as $k=>$v){
					$v["user"]=$us[$v["userid"]];
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
			$this->smarty->display("fishing_free_order/index.html");
		}
		
		public function onMy(){
			M('login')->checkLogin();
			$userid=M("login")->userid;
			$where=" userid=".$userid." AND status in(0,1,2) AND ispay=1 ";
			$url="/module.php?m=fishing_free_order&a=my";
			$limit=20;
			$start=get("per_page","i");
			$option=array(
				"start"=>$start,
				"limit"=>$limit,
				"order"=>" orderid DESC",
				"where"=>$where
			);
			$rscount=true;
			$data=M("mod_fishing_free_order")->select($option,$rscount);
			if(!empty($data)){
				$placeids=[];
				foreach($data as $v){
					$placeids[]=$v["placeid"];
				}
				$places=MM("fishing","fishing_free_place")->getListByIds($placeids);
				foreach($data as $k=>$v){
					$v["place"]=$places[$v["placeid"]];
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
			$this->smarty->display("fishing_free_order/my.html");
		}
		
		
		public function onAdd(){
			$orderid=get_post("orderid","i");
			if($orderid){
				$data=M("mod_fishing_free_order")->selectRow(array("where"=>"orderid=".$orderid));
				
			}
			$this->smarty->goassign(array(
				"data"=>$data
			));
			$this->smarty->display("fishing_free_order/add.html");
		}
		
		public function onSave(){
			$orderid=get_post("orderid","i");
			$data=M("mod_fishing_free_order")->postData();
			if($orderid){
				M("mod_fishing_free_order")->update($data,"orderid=".$orderid);
			}else{
				M("mod_fishing_free_order")->insert($data);
			}
			$this->goall("保存成功");
		}
		
		public function onStatus(){
			$orderid=get_post('orderid',"i");
			$row=M("mod_fishing_free_order")->selectRow("orderid=".$orderid);
			if($row["status"]==1){
				$status=2;
			}else{
				$status=1;
			}
			 
			M("mod_fishing_free_order")->update(array("status"=>$status),"orderid=".$orderid);
			$this->goall("状态修改成功",0,$status);
		}
		
		public function onOrder(){
			M("login")->checkLogin();
			$userid=M("login")->userid;
			$placeid=get_post("placeid","i");
			$money=post("money","i");
			$desc=post("description","h");
			$orderid=M("mod_fishing_free_order")->insert(array(
				"placeid"=>$placeid,
				"userid"=>$userid,
				"money"=>$money,
				"description"=>$desc,
				"createtime"=>date("Y-m-d H:i:s")
			)); 
			//支付
			$backurl="/module.php?m=fishing_free_order&a=success&orderid=".$orderid;
			$orderdata=array(
				"table"=>"plugin",
				"callback"=>'
					MM("fishing","fishing_free_order")->order('.$orderid.');
				',
				"url"=>$backurl
			);
			$orderdata=base64_encode(json_encode($orderdata)); 
			$orderinfo="购买鱼苗放流";
			$pay_type=INWEIXIN?"wxpay":"alipay";
			$fromapp=get("fromapp");
			$orderno="fingshing_free_order_".$orderid;
			$openid=get('openid','h');
			//固定支付
			$rechargeid=M("recharge")->insert(array(
				"orderno"=>$orderno,
				"userid"=>$userid,
				"money"=>$money,
				"pay_type"=>$pay_type,
				"dateline"=>time(),
				"openid"=>$openid,
				"orderinfo"=>$orderinfo, 
				"orderdata"=>$orderdata,
				"status"=>2,
			));
			$bank_type="";
			$order_product=$orderinfo;
			$url=HTTP_HOST."/index.php?m=recharge_{$pay_type}&a=go";
			$url.="&orderno=$orderno";
			$url.="&bank_type=".$bank_type;
			$url.="&order_product=".urlencode($order_product);
			$url.="&order_price=".$money;
			$url.="&order_info=".urlencode($order_info);
			$url.="&backurl=".urlencode($backurl);
			$redata=array(
				"payurl"=>$url,
				"action"=>"pay",
				"orderno"=>$orderno
			);
			if($return){
				return $redata;
			}
			//end 固定支付
			$this->goALl("正在前往支付",0,$redata,$url);
			$this->goAll("success");
			 
		}
		
		public function onSuccess(){
			$this->smarty->display("fishing_free_order/success.html");
		}
	}

?>